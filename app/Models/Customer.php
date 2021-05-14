<?php

namespace App\Models;

use App\ModelTraits\ShopifyFill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Customer
 * @package App\Models
 *
 * @property int shopify_id
 * @property string email
 * @property string first_name
 * @property string last_name
 * @property string full_name
 */
class Customer extends Model
{
    use HasFactory;
    use ShopifyFill;

    protected $fillable = ['*'];

    protected $fillableExceptions = ['created_at', 'updated_at'];

    protected $appends = ['full_name', 'OrdersCount'];

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

    public function LastFeedback()
    {
        return $this->hasMany(Feedback::class, 'customer_id', '_id')
            ->orderBy('created_at', 'desc')
            ->limit(1);
    }

    public function LastOrder()
    {
        return $this->Orders()
            ->orderBy('created_at', 'desc')
            ->limit(1);
    }
}
