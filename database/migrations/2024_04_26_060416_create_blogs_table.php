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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('slug', 100)->unique();
            $table->string('image', 100)->nullable();
            $table->text('description');
            $table->text('content');
            $table->unsignedInteger('view_count')->default(0);
            $table->text('tags');
            $table->integer('status')->default('1');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('admin_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
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
