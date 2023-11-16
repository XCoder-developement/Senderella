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
        Schema::create('user_search_requirments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_search_id')->constrained('user_searches')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('requirment_id')->unsigned()->nullable();
            $table->foreign('requirment_id')->references('id')->on('requirments')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('requirment_item_id')->unsigned()->nullable();
            $table->foreign('requirment_item_id')->references('id')->on('requirment_items')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_search_requirments');
    }
};
