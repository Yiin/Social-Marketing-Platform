<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueLog extends Model
{
    protected $fillable = ['queue_id', 'log'];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function getLogAttribute()
    {
        return json_decode($this->getOriginal('log'));
    }
}
