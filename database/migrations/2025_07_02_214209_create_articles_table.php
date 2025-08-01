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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('author')->nullable()->index();
            $table->longText('content')->nullable();
            $table->text('url')->nullable();
            $table->text('image_url')->nullable();
            $table->string('source')->nullable();
            $table->string('data_source')->nullable()->index();
            $table->date('published_at')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('tag_id')->on('tags')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
