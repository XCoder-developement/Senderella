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
        Schema::create('work_types', function (Blueprint $table) {
            $table->increments("id");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('work_type_translations', function (Blueprint $table) {
            $table->increments("id");
            $table->integer('work_type_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('locale')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('work_type_id')->references('id')->on('work_types')
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
        Schema::dropIfExists('work_types');
    }
};
