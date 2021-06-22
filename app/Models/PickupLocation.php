<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class PickupLocation
 * @package App\Models
 *
 * @property string name
 */
class PickupLocation extends Model
{
    use HasFactory;

    const PICKUP_OTHER = 'Other';

    protected $fillable = ['name'];

    public function scopeList($query)
    {
        return $query->orderBy('name', 'ASC')->select('name', '_id');
    }
}
