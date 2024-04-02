<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delted_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('birthday_date')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('nationality_id')->unsigned()->nullable();

            $table->integer('marital_status_id')->unsigned()->nullable();
            $table->integer('marriage_readiness_id')->unsigned()->nullable();
            $table->integer('education_type_id')->unsigned()->nullable();
            $table->integer('color_id')->unsigned()->nullable();
            $table->text('weight')->nullable();
            $table->text('height')->nullable();
            $table->integer('active')->nullable();
            $table->integer('trusted')->nullable(); // if trusted 1 user deleted if 2 admin deleted 

            $table->integer('is_verify');  // the status of having packages or not the default is 0 everything is available exception messaging and hashing the recieved messages , 1 has a package

            $table->string('image')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('about_me')->nullable();
            $table->longText('important_for_marriage')->nullable();
            $table->longText('partner_specifications')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1 => male , 2 => female');
            $table->tinyInteger('is_married_before')->nullable()->comment('2 => no , 1 => yes');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delted_users');
    }
};
