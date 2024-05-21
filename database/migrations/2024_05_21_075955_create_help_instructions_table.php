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
        Schema::create('help_instructions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
        Schema::create('help_instruction_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('help_instruction_id')->nullable();
            $table->longText('text')->nullable();
            $table->string('locale')->index();
            $table->foreign('help_instruction_id')->references('id')->on('help_instructions')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_instructions');
        Schema::dropIfExists('help_instruction_translations');
    }
};
