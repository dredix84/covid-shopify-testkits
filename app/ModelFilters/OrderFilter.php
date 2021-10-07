<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class OrderFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];


    public function pickupLocation($value)
    {
        return $this->where('pickup_location', $value);
    }

    public function financialStatus($value)
    {
        return $this->where(function ($query) use ($value) {
            if (!blank($value)) {
                $query->where('financial_status', $value);
            }
        });
    }

    public function fulfillmentStatus($value)
    {
        return $this->where(function ($query) use ($value) {
            if (!blank($value)) {
                $query->where('fulfillment_status', $value);
            }
        });
    }


    public function term($value)
    {
        if (is_numeric($value)) {
            return
                $this->where('order_number', (int) $value);;
        } else {
            return $this
                ->related('customer', function ($query) use ($value) {
                    return $query
                        ->where('last_name', 'regexp', sprintf('/%s/i', $value))
                        ->orWhere('first_name', 'regexp', sprintf('/%s/i', $value))
                        ->orWhere('email', 'regexp', sprintf('/%s/i', $value));
                });
        }
    }
}
