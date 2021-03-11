<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    //
    protected $table = 'form_input';
    
    protected $fillable  = [
        'price','form_group_id','label','description','placeholder','class','required','visible_applicant','status', 'type', 'sort', 'settings', 'application_status_message', 'restriction'
    ];
}