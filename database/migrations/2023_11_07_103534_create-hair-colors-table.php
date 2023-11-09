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
        //
        Schema::create('hair_colors', function (Blueprint $table) {
            $table->increments("id");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('hair_color_translations', function (Blueprint $table) {
            $table->increments("id");
            $table->integer('hair_color_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('locale')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('hair_color_id')->references('id')->on('hair_colors')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('hair_colors');
    }
};
