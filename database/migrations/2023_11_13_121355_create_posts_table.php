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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->nullable()->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->smallInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->timestamps();
        });
        Schema::create('post_translations', function (Blueprint $table) {
            $table->id();
            $table->string('post')->nullable();
            $table->string('locale')->index();
            $table->foreign('post_id')->references('id')->on('posts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
