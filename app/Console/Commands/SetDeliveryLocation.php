<?php

namespace App\Console\Commands;

use App\Helpers\Util;
use App\Models\Order;
use App\Models\PickupLocation;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SetDeliveryLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:set-pickup-location';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to set the pickup_location for orders missing that field';

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
        $pickLocations = [
            'Other' => 'Other'
        ];

        $ordersQuery = Order::select(['note_attributes', 'order_number'])
            ->where('pickup_location', 'exists', false)
            ->orderBy('order_number', 'DESC');

        $documentToProcess = $ordersQuery->count();
        $this->info('Documents to process: '.$documentToProcess);
        $bar = $this->output->createProgressBar($documentToProcess);
        $bar->start();

        $ordersQuery->chunk(100, function ($orders) use (&$pickLocations, &$bar) {
            foreach ($orders as $order) {
                /** @var Order $order */
//                dd($order->toArray());
//                $filtered = Arr::where($order->note_attributes, function ($value, $key) {
//                    return in_array($value['name'], ['Pickup-Location-Company', 'Order Location']);
//                });
//
//                $pickLocation = PickupLocation::PICKUP_OTHER;
//                if (count($filtered)) {
//                    $location                     = Arr::first($filtered);
//                    $pickLocation                 = $location['value'];
//                    $pickLocations[$pickLocation] = $pickLocation;
//                }
//                $order->pickup_location = $pickLocation;
//                $order->save();

                $pickupLocation                 = $order->getShopifyPickLocation();
                $order->pickup_location         = $pickupLocation;
                $pickLocations[$pickupLocation] = $pickupLocation;
                $order->save();

                $bar->advance();
            }
        });
        $bar->finish();

        //Saving all pickup locations
        foreach ($pickLocations as $pickLocation) {
            PickupLocation::firstOrCreate([
                'name' => $pickLocation
            ]);
        }

        $this->info("\nOperation completed");
        return 0;
    }
}
