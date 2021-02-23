<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormGroup extends Model
{
    //
    protected $table = 'form_group';

    public function inputs() {
        return $this->hasMany(FormInput::class)->orderBy('form_input.sort', 'ASC')->orderBy('form_input.id', 'ASC');
    }

    public function inputs_w_value($user_id) {
        return $this->inputs()
        ->select('form_input.*', 'posts.post', 'posts.input_id')
        ->leftJoin('posts', function($join) use ($user_id){
            $join->on('form_input.id', '=', 'posts.input_id')
            ->where('posts.user_id', '=', $user_id);
        })
        ->get();
    }


    public function application_status_message($user_id) {
        return $this->hasMany(FormInput::class)
        ->leftJoin('posts', function($join) {
            $join->on('form_input.id', '=', 'posts.input_id');
        })->select( 'form_input.*' )
        ->where('posts.user_id', $user_id)
        ->whereNotNull('form_input.application_status_message')
        ->whereNotNull('posts.post')
        ->orderBy('form_input.id', 'desc')
        ->first();
    }

    public function application_status_report() {
        return $this->inputs()
        ->where('form_input.application_status_message', '<>', null);
    }


}

