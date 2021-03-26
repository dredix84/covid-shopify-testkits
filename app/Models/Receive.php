<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Receive
 * @package App\Models
 * @property array header
 * @property array payload
 */
class Receive extends Model
{
    use HasFactory;

    protected $casts = [];
}
