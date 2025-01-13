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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Title of the book
            $table->string('author'); // Author of the book
            $table->string('isbn')->unique(); // ISBN of the book (unique identifier for books)
            $table->text('description')->nullable(); // Description or summary of the book
            $table->string('genre'); // Genre or category (e.g., Fiction, History, Science)
            $table->integer('pages'); // Number of pages in the book
            $table->year('publication_year'); // Publication year of the book
            $table->string('cover_image')->nullable(); // URL or path to the cover image
            $table->integer('available_quantity')->default(0); // The number of available copies
            $table->integer('borrowed_quantity')->default(0); // The number of borrowed copies
            $table->boolean('is_active')->default(true); // If the book is active and available for borrowing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
