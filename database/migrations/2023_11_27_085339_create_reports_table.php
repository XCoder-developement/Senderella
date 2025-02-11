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
        Schema::create('reports', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('partner_id')->nullable();

            $table->longText('reason')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();


            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('report_points', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger('report_type_id')->nullable();
            $table->unsignedInteger('report_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('report_type_id')->references('id')->on('report_types')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
