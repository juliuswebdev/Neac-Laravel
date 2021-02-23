<?php

//This is for User's Input

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'category_id','name','description', 'logo', 'image'
    ];

    protected $table = 'service_categories';



}
