<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $table = 'notifications';
    protected $fillable  = [
        'from_user_id', 'to_user_id', 'module', 'messages', 'url'
    ];

    function user() {
        return $this->hasOne(User::class,'id','from_user_id');
    }
}
