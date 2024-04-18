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
        Schema::create('chat_message_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('chat_message_id')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->integer('type')->nullable();

            $table->foreign('chat_message_id')->references('id')->on('chat_messages')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_message_media');
    }
};
