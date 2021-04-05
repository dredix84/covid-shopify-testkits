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

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }
}
