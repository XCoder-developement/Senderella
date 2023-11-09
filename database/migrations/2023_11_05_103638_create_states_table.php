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
        Schema::create('states', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("country_id")->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('country_id')->references('id')->on('countries')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::create('state_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('state_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('locale')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('state_id')->references('id')->on('states')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
