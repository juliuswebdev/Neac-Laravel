<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FormInput extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_input', function (Blueprint $table) {
            //
            $table->id();
            $table->integer('form_group_id');
            $table->integer('sort')->default(0);
            $table->string('label');
            $table->string('description')->nullable();
            $table->string('settings');
            $table->string('placeholder');
            $table->string('class');
            $table->integer('required')->default(0);
            $table->string('type');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('form_input', function (Blueprint $table) {
            //
        });
    }
}
