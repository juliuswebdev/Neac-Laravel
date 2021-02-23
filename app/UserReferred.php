<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReferred extends Model
{
    //
    protected $table = 'users_referred';
    
    protected $fillable  = [
        'user_id', 'reseller_code_used', 'first_name', 'middle_name', 'last_name', 'email', 'mobile_number'
    ];

}
