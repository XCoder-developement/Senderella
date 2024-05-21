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
        Schema::create('use_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
        Schema::create('use_method_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('use_method_id')->nullable();
            $table->longText('text')->nullable();
            $table->string('locale')->index();
            $table->foreign('use_method_id')->references('id')->on('use_methods')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('use_methods');
        Schema::dropIfExists('use_method_translations');
    }
};
