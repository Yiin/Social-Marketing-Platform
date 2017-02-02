<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookQueue extends Model
{
    protected $fillable = [
        'queue_id',
        'caption',
        'name',
        'message',
        'url',
        'image_url',
        'description',
        'communities_reached',
        'impressions',
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
