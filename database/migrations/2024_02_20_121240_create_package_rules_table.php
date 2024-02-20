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
        Schema::create('package_rules', function (Blueprint $table) {
            $table->increments("id");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        Schema::create('package_rule_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_rule_id')->unsigned()->nullable();
            $table->longText('text')->nullable();
            $table->string('locale')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('package_rule_id')->references('id')->on('package_rules')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_rules');
        Schema::dropIfExists('package_rule_translations');
    }
};
