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
        Schema::create('report_types', function (Blueprint $table) {
            $table->increments("id")->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('report_type_translations', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger('report_type_id')->nullable();
            $table->string('title')->nullable();
            $table->string('locale')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('report_type_id')->references('id')->on('report_types')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_types');
        Schema::dropIfExists('report_type_translations');

    }
};
