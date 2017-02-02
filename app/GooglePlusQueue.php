<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GooglePlusQueue extends Model
{
    protected $fillable = [
        'queue_id',
        'message',
        'url',
        'isImageUrl',
        'communities_reached',
        'impressions'
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

}
