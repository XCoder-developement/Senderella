<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_searches', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('word')->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('nationality_id')->unsigned()->nullable();
            $table->integer('weight')->unsigned()->nullable();
            $table->integer('height')->unsigned()->nullable();
            $table->integer('marital_status_id')->unsigned()->nullable();
            $table->integer('age_from')->unsigned()->nullable();
            $table->integer('age_to')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_searches');
    }
};
