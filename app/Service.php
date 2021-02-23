<?php

//The pre-made data are stored here


namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'name','type','price','description', 'sort', 'status', 'state', 'tax'
    ];

    protected $table = 'services';

    public function service_category() {
        return $this->hasOne(ServiceCategory::class, 'category_id', 'category_id');
    }



}
