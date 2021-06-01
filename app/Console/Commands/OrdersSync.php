<?php

namespace App\Console\Commands;

use App\Events\ReceivedOrder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Receive;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrdersSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command used to sync all orders from the Shopify shop';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $client = new Client([
            'base_uri' => config('shopify.api.base_url'),
            'headers'  => [
                'X-Shopify-Access-Token' => config('shopify.api.token')
            ]
        ]);

        $apiUrl = config('shopify.api.base_url').'/admin/api/2021-04/orders.json?status=any&limit=250';
        while (!blank($apiUrl)) {
            $response = $client->request('GET', $apiUrl);
            $body     = $response->getBody();

            if (!blank($response->getHeader('link'))) {
                $linkParts = explode(',', $response->getHeader('link')[0]);
                if (isset($linkParts[0]) && Str::contains($linkParts[0], 'rel="next"')) {
                    $linkParts = explode(';', $linkParts[0]);
                } elseif (isset($linkParts[1]) && Str::contains($linkParts[1], 'rel="next"')) {
                    $linkParts = explode(';', $linkParts[1]);
                } else {
                    $linkParts = null;
                }

                if ($linkParts) {
                    $link   = str_replace(['<', '>'], '', $linkParts[0]);
                    $apiUrl = !blank($link) ? trim($link) : null;
                } else {
                    $apiUrl = null;
                }

            } else {
                $apiUrl = null;
            }
            $orderData = json_decode((string) $body, true);

            $this->info('Orders to process: '.count($orderData['orders']));
            foreach ($orderData['orders'] as $order) {
                $this->info('Processing order: '.$order['order_number']);
                $received          = new Receive();
                $received->headers = [
                    "x-shopify-topic" => "orders/updated"
                ];
                $received->payload = $order;
                $received->topic   = "orders/updated";
                ReceivedOrder::dispatch($received);
            }

            if (!blank($apiUrl)) {
                $this->comment('Waiting 1 secs before next request.');
                sleep(1);
            }
        }

        $this->info('Now updating customer data with last order');
        $customers = Customer::all();
        $bar       = $this->output->createProgressBar(count($customers));
        $bar->start();
        foreach ($customers as $customer) {
            /** @var Customer $customer */
            $lastOrder = Order::where('email', $customer->email)
                ->orderBy('order_number', 'desc')
                ->first();
            if ($lastOrder) {
                $customer->last_order = $lastOrder->toArray();
                $customer->save();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->comment("\nOperation Complete");
        return 0;
    }
}
