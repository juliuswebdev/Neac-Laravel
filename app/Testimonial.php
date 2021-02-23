<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    //
    protected $table = 'testimonials';
    
    protected $fillable  = [
        'subject','description','rating','url','attachments', 'user_id', 'image', 'video', 'status', 'category', 'applicant_name', 'applicant_image'
    ];

    function user() {
        return $this->hasOne(User::class,'id','user_id');
    }

    function user_testimonial() {
        return $this->user()->select('id','first_name','last_name');
    }

}
