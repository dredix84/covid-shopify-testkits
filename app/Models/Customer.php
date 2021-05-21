<?php

namespace App\Models;

use App\Helpers\ExceptionHelper;
use App\ModelTraits\ShopifyFill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Customer
 * @package App\Models
 *
 * @property string id
 * @property int shopify_id
 * @property string email
 * @property string first_name
 * @property string last_name
 * @property string full_name
 *
 * @property array last_order
 * @property array last_feedback
 * @property int total_administered
 */
class Customer extends Model
{
    use HasFactory;
    use ShopifyFill;

    protected $fillable = ['*'];

    protected $fillableExceptions = ['created_at', 'updated_at'];

    protected $appends = ['full_name', 'OrdersCount', 'item_count'/*, 'last_feedback'*/];

    protected $casts = [
//        'last_feedback' => 'array',
//        'last_order'    => 'array',
    ];

    public function getFullNameAttribute()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }

    public function getOrdersCountAttribute()
    {
        return $this->Orders()->count();
    }

    public function Orders()
    {
        return $this->hasMany(Order::class, 'email', 'email');
    }

    public function getItemCountAttribute()
    {
        try {
            if (!blank($this->email)) {
                $order = Order::select('line_items.quantity')
                    ->where('email', $this->email)
                    ->where('fulfillment_status', 'fulfilled')
                    ->get();
                if ($order) {
                    return $order->pluck('line_items.*.quantity')->flatten()->sum();
                }
            }
        } catch (\Exception $e) {
            ExceptionHelper::logError($e, 'getOrderQuantitiesAttribute: Error while calculating total items ordered.');
        }

        return 0;
    }

    /*public function getLastFeedbackAttribute()
    {
        if (!blank($this->id)) {
            return Feedback::where('customer_id', $this->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        return null;
//        return null;
//        return $this->hasMany(Feedback::class, 'customer_id', '_id')
//            ->orderBy('created_at', 'desc')
//            ->limit(1);
    }*/

    public function getNewestOrderAttribute()
    {
        if (!blank($this->id)) {
            return Order::where('email', $this->email)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        return null;
//        return $this->Orders()
//            ->orderBy('created_at', 'desc')
//            ->limit(1);
    }
}
