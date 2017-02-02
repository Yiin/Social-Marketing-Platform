<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedInQueue extends Model
{
    protected $fillable = [
        'title',
        'details',
        'url',
        'image_url',
        'description'
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
