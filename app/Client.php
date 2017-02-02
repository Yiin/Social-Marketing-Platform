<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'notes'];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
