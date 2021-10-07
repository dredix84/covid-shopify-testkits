<?php

namespace App\Models;

use App\Helpers\Util;
use App\ModelTraits\ShopifyFill;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Order
 * @package App\Models
 *
 * @property int id
 * @property int order_number
 * @property string pickup_location
 */
class Order extends Model
{
    use HasFactory;
    use ShopifyFill;
    use Filterable;

    const SHOPIFY_HEADER_TOPIC_CREATE  = 'orders/create';
    const SHOPIFY_HEADER_TOPIC_UPDATED = 'orders/updated';

    protected $fillable = ['*', 'created_at', 'updated_at'];

    protected $fillableExceptions = ['created_at', 'updated_at', 'customer'];

    protected $with = ['customer'];

    protected $casts = [
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'processed_at' => 'datetime',
    ];

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

    public function getShopifyPickLocation()
    {
        $pickLocation = PickupLocation::PICKUP_OTHER;
        if ($this->note_attributes) {
            $filtered = Arr::where($this->note_attributes, function ($value, $key) {
                return in_array($value['name'], ['Pickup-Location-Company', 'Order Location']);
            });
            if (count($filtered)) {
                $location     = Arr::first($filtered);
                $pickLocation = $location['value'];
            }
        }

        return $pickLocation;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'email', 'email');
    }


}
