<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['queue_id', 'group', 'url', 'message'];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}
