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
        Schema::create('requirment_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requirment_id')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('requirment_id')->references('id')->on('requirments')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('requirment_item_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requirment_item_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('locale')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('requirment_item_id')->references('id')->on('requirment_items')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirment_items');
    }
};
