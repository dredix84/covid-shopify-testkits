<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Feedback
 * @package App\Models
 *
 * @property string id
 * @property string customer_id
 * @property int employee_count
 * @property int employee_participating
 * @property int antigen_tests_administered
 * @property int presumptive_positive
 * @property int presumptive_negative
 * @property int inconclusive
 * @property int experience_rating
 * @property string comment
 * @property Carbon created_at
 * @property Carbon updated_at
 */
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

    protected $appends = ['formatted_date'];

    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('lll') : null;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function feedbackCustomer()
    {
        return $this->customer()
            ->select(['last_order', 'last_order_id', 'email', 'shopify_id']);
    }
}
