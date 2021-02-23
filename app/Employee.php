<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'image','mother_name','alternate_email','security_answer',
        'birth_date','telephone_number','mobile_number','city',
        'country','postal_code','state','gender','home_address', 'employee_number'
    ];

    protected $table = 'employees';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getcountry() 
    {
        return $this->hasOne(Country::class,'code', 'country');
    }
}