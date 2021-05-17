<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Receive
 * @package App\Models
 * @property array headers
 * @property array payload
 * @property string topic
 */
class Receive extends Model
{
    use HasFactory;

    protected $casts = [
//        'payload' => 'json'
    ];

    public function fillHeaders($headers)
    {
        $topicKey = config('shopify.headers.topic');

        $fixedHeader = [];
        foreach ($headers as $key => $value) {
            $fixedHeader[$key] = is_array($value) && count($value) === 1 ? $value[0] : $value;
            if ($topicKey === $key) {
                $this->topic = $value;
            }
        }
        $this->headers = $fixedHeader;
        return $this;
    }
}
