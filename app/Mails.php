<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mails extends Model
{
    //
    protected $table = 'mails';
    protected $fillable  = [
        'user_id', 'to', 'cc', 'bcc', 'attachments', 'subject', 'messages'
    ];

    function user() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
