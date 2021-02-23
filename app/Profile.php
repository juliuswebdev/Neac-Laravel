<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'image','alternate_email','telephone_number','mobile_number', 
        'forms', 'application_status', 'profile', 'application_number'
    ];

    protected $table = 'profiles';

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

    public function country() {
        return $this->hasOne(Country::class,'country_code', 'country');
    }
}
