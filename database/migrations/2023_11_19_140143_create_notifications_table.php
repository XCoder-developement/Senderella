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
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id')->nullable();
            $table->text('body')->nullable();
            $table->text('title')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_translations', function (Blueprint $table) {
            $table->increments('id')->nullable();
            $table->integer('notification_id')->nullable();
            $table->text('body')->nullable();
            $table->text('title')->nullable();
            $table->string('locale')->index();
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
