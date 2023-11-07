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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->date('birthday_date')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->string('nationality_id')->nullable();
            $table->text('weight')->nullable();
            $table->text('height')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('about_me')->nullable();
            $table->longText('important_in_marriage')->nullable();
            $table->longText('partner_specifications')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('0 => male , 1 => female');
            $table->tinyInteger('is_married_before')->nullable()->comment('0 => no , 1 => yes');
            $table->tinyInteger('verification_type')->nullable()->comment('0 => phone , 1 => email');
            $table->string('verification_code')->nullable();
            $table->tinyInteger('is_verified')->unsigned()->default(0);

            $table->rememberToken();
            $table->text('api_token')->nullable();
            $table->timestamps();

        });

        Schema::create('user_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->text('device_token')->nullable();
            $table->text('device_id')->nullable();
            $table->text('device_type')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('user_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('image')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();


            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
