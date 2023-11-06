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
            $table->string('gender')->nullable();
            $table->string('verification_code')->nullable();
            $table->tinyInteger('verification_type')->nullable()->comment('0 => phone , 1 => email');
            $table->tinyInteger('type')->nullable();
            $table->date('birthday_date')->nullable();
            $table->string('password');
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();

            $table->string('nationality_id')->nullable();

            $table->tinyInteger('marital_status')->nullable()->comment('0 => single , 1 => married');

            $table->tinyInteger('is_married_before')->nullable()->comment('0 => no , 1 => yes');
            $table->integer('readiness_for_marriage')->default(0)->comment('0 => as soon as possible');
            $table->text('weight')->nullable();
            $table->text('height')->nullable();
            $table->longText('notes')->nullable();


            $table->tinyInteger('is_verified')->unsigned()->default(0);
            $table->string('invitation_code')->nullable();
            $table->string('invite_code')->nullable();


            $table->rememberToken();
            $table->text('api_token')->nullable();
            $table->timestamps();

            // $table->foreign('country_id')->references('id')->on('countries')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
            // $table->foreign('state_id')->references('id')->on('states')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
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
