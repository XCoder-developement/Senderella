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
            $table->integer('nationality_id')->unsigned()->nullable();
            $table->integer('marital_status_id')->unsigned()->nullable();
            $table->integer('marriage_readiness_id')->unsigned()->nullable();
            $table->integer('education_type_id')->unsigned()->nullable();
            $table->integer('color_id')->unsigned()->nullable();
            $table->text('weight')->nullable();
            $table->text('height')->nullable();
            $table->integer('active')->nullable()->default(0);
            $table->integer('trusted')->nullable()->default(0);

            $table->longText('notes')->nullable();
            $table->longText('about_me')->nullable();
            $table->longText('important_for_marriage')->nullable();
            $table->longText('partner_specifications')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1 => male , 2 => female');
            $table->tinyInteger('is_married_before')->nullable()->comment('2 => no , 1 => yes');
            $table->tinyInteger('verification_type')->nullable()->comment('0 => phone , 1 => email');
            $table->string('verification_code')->nullable();
            $table->tinyInteger('phone_verify')->unsigned()->default(0);
            $table->tinyInteger('email_verify')->unsigned()->default(0);

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

        Schema::create('user_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('requirment_id')->unsigned()->nullable();
            $table->integer('requirment_item_id')->unsigned()->nullable();
            $table->longText('answer')->nullable();
            $table->integer('type')->unsigned()->nullable()->comment('1 => requirment , 2 => question');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
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


        Schema::create('user_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('package_id')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('user_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('partner_id')->unsigned()->nullable()->comment('liked_user');
            $table->integer('like_count')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('user_bookmarks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('partner_id')->unsigned()->nullable()->comment('bookmarked_user');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('user_watches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('partner_id')->unsigned()->nullable()->comment('watched_user');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });


        Schema::create('user_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->comment('blocker');
            $table->integer('partner_id')->unsigned()->nullable()->comment('blocked_parteners');
            $table->text('text')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('user_block_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_block_id')->unsigned()->nullable()->comment('blocker');
            $table->integer('block_reason_id')->unsigned()->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();


            $table->foreign('user_block_id')->references('id')->on('user_blocks')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('user_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('notification_id')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();


            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('notification_id')->references('id')->on('users')
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
