<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    //
    protected $table = 'mail_template';

    protected $fillable = [
        'id', 'module', 'description', 'header', 'body', 'footer', 'to_mail', 'cc_mail', 'subject_mail'
    ];
}
