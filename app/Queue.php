<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    const TYPE_GP = 'google-plus';
    const TYPE_FB = 'facebook';
    const TYPE_LI = 'linkedin';
    const TYPE_TW = 'twitter';

    protected $fillable = ['type', 'client_id'];

    public function log()
    {
        $this->hasMany(QueueLog::class);
    }

}
