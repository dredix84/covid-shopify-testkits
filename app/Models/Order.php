<?php

namespace App\Models;

use App\ModelTraits\ShopifyFill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use ShopifyFill;


    protected $fillable = ['*'];

    protected $fillableExceptions = ['created_at', 'updated_at', 'customer'];

//    /**
//     * @param $order
//     * @return $this
//     */
//    public function fillFromShopify($order): Order
//    {
//        foreach ($order as $key => $value) {
//            if (!in_array($key, $this->fillable_exceptions, true)) {
//                if ($key === 'id') {
//                    $this->shopify_id = $value;
//                } else {
//                    $this->{$key} = $value;
//                }
//            }
//        }
//        return $this;
//    }
}
