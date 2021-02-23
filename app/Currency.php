<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $table = 'currency';

    protected $fillable = [
        'name', 'code', 'value', 'additional', 'vat'
    ];
}
