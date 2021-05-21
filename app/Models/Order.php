<?php

namespace App\Models;

use App\Helpers\Util;
use App\ModelTraits\ShopifyFill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use ShopifyFill;

    const SHOPIFY_HEADER_TOPIC_CREATE  = 'orders/create';
    const SHOPIFY_HEADER_TOPIC_UPDATED = 'orders/updated';

    protected $fillable = ['*'];

    protected $fillableExceptions = ['created_at', 'updated_at', 'customer'];

    protected $with = ['customer'];

    /**
     * Used to determine if an order should be created based on the header
     * @param  string  $header
     * @return bool
     */
    public static function isAllowedHeader($header): bool
    {
        $allowed = config('shopify.allow_headers.orders');
        if (in_array($header, $allowed, true) || in_array('*', $allowed, true)) {
            return true;
        }
        return false;
    }

    public static function getShippingTitles()
    {
        $outData = [];
        try {
            $values = self::distinct('shipping_lines.title')
                ->get();
            if ($values) {
                foreach ($values as $value) {
                    $outData[] = [
                        'value' => $value->toArray()[0],
                        'label' => $value->toArray()[0]
                    ];
                }
            }
        } catch (\Exception $e) {

        }
        return $outData;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'email', 'email');
    }


}
