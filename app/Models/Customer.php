<?php

namespace App\Models;

use App\Helpers\ExceptionHelper;
use App\Helpers\Util;
use App\ModelTraits\ShopifyFill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
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
 * @property int item_count
 */
class Customer extends Model
{
    use HasFactory;
    use ShopifyFill;

    protected $fillable = ['*', 'email'];

    protected $fillableExceptions = ['created_at', 'updated_at'];

    protected $appends = [
        'full_name',
        'OrdersCount',
        'item_count'/*, 'last_feedback'*/,
        'allow_fulfillment',
        'percentage_administered'
    ];

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

    /**
     * THis functions tries to figure out the postal code for the last pickup location the customer ordered from
     * @return string|null
     */
    public function getLastPickupPostalCodeAttribute(): ?string
    {
        $postalCode = null;
        try {
            if (isset($this->last_order) && !blank($this->last_order['note_attributes'])) {
                $postalCode = Util::getPostalCodeFromOrderNotesArray($this->last_order['note_attributes']);
            }

            if ($postalCode === null) {
                $lastOrder = $this->getNewestOrderAttribute();

                $postalCode = Util::getPostalCodeFromOrderNotesArray($lastOrder->note_attributes);
                if ($postalCode === null && isset($this->getNewestOrderAttribute()->line_items[0]['origin_location'])) {
                    $postalCode = $this->getNewestOrderAttribute()->line_items[0]['origin_location']['zip'];
                }
                if ($postalCode === null && isset($this->getNewestOrderAttribute()->shipping_address)) {
                    $postalCode = $this->getNewestOrderAttribute()->shipping_address['zip'];
                }
            }

        } catch (\Exception $e) {
            $postalCode = 'Unknown';
        }

        return $postalCode;
    }

    public function getLastPickLocationNameAttribute()
    {
        $locationName = null; //Pickup-Location-Company
        if (isset($this->last_order) && !blank($this->last_order['note_attributes'])) {
            $data = Arr::first($this->last_order['note_attributes'], function ($value, $key) {
                return $value['name'] === 'Pickup-Location-Company';
            });
            if ($data) {
                $locationName = $data['value'];
            }
        }

        return $locationName;
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

    /**
     * Calculates the percentage of kits administered
     * @return false|float|int
     */
    public function getPercentageAdministeredAttribute()
    {
        $outValue = 0;
        try {
            if (isset($this->total_administered, $this->item_count) && $this->item_count > 0) {
                $outValue = $this->total_administered / ($this->item_count * 25) * 100;
            }
        } catch (\Exception $e) {
            ExceptionHelper::logError($e, 'Error calculating the percentage administered for '.$this->email);
        }

        return $outValue > 0 && $outValue <= 100 ? floor($outValue) : 0;
    }

    /**
     * Used to determine is the customer should be give more kits. Returns true if should allow
     * @return bool|string
     */
    public function getAllowFulfillmentAttribute()
    {
        try {
            if ($this->getItemCountAttribute() > 0 && !blank($this->last_order)) {
                if ($this->last_order && $this->last_feedback === null) {
                    return 'No feedback submitted';
                }

                //Determining if the user has used less than the allowed amount before reorder
                $minReorderPercent      = config('shopify.min_reorder_percent');
                $percentageAdministered = $this->getPercentageAdministeredAttribute();
                if ($percentageAdministered < config('shopify.min_reorder_percent')) {
                    return sprintf('Less than %s%% used.', $minReorderPercent);
                }
                if ($percentageAdministered >= 100) {
                    return true;
                }

                // Determining if the user submitted feedback
                if (isset($this->last_order['created_at'])) {
                    $lastOrderCreatedAt = $this->last_order['created_at'];
                } else {
                    $lastOrder          = Order::where('order_number', $this->last_order['order_number'])->first();
                    $lastOrderCreatedAt = $lastOrder->created_at ?? null;

                    $tempLastOrder               = $this->last_order;
                    $tempLastOrder['created_at'] = $lastOrderCreatedAt;
                    $this->last_order            = $tempLastOrder;
                }

                if ($this->last_order && $this->last_feedback && $lastOrderCreatedAt > $this->last_feedback['created_at']) {
                    return 'No feedback submitted after last order';
                }


            }
        } catch (\Exception $e) {
            ExceptionHelper::logError(
                $e,
                "There was an error which attempting to calculate the allow fulfillment status for ".$this->email
            );
            return "Unable to calculate";
        }

        return true;
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
            $lastOrderId = $this->last_order_id ?? null;
            $email       = $this->email ?? null;
            return Order::where(function ($query) use ($email, $lastOrderId) {
                $query->where('email', $email);
                if ($lastOrderId) {
                    $query->orWhere('shopify_id', $lastOrderId);
                }
            })
                ->orderBy('created_at', 'desc')
                ->first();
        }
        return null;
    }
}
