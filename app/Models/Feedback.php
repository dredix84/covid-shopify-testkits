<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'antigen_tests_administered',
        'comment',
        'customer_id',
        'employee_count',
        'employee_participating',
        'experience_rating',
        'inconclusive',
        'presumptive_negative',
        'presumptive_positive',
    ];
}
