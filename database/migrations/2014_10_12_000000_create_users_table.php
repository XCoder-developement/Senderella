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
            $table->string('image')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->double('points')->nullable();
            $table->string('password');
            $table->tinyInteger('is_verified')->unsigned()->default(0);
            $table->string('invitation_code')->nullable();
            $table->string('invite_code')->nullable();

            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->integer('zone_id')->unsigned()->nullable();

            $table->rememberToken();
            $table->text('api_token')->nullable();
            $table->timestamps();

            // $table->foreign('state_id')->references('id')->on('states')
            // ->onUpdate('cascade')
            // ->onDelete('cascade');
            // $table->foreign('city_id')->references('id')->on('cities')
            // ->onUpdate('cascade')
            // ->onDelete('cascade');
            // $table->foreign('zone_id')->references('id')->on('zones')
            // ->onUpdate('cascade')
            // ->onDelete('cascade');
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

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
