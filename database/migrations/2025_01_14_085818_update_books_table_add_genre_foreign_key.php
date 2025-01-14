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
        Schema::table('books', function (Blueprint $table) {
            // Drop the old 'genre' column
            $table->dropColumn('genre');

            // Add the new 'genre_id' column
            $table->unsignedBigInteger('genre_id')->nullable();

            // Set up the foreign key constraint
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['genre_id']);

            // Drop the 'genre_id' column
            $table->dropColumn('genre_id');

            // Recreate the old 'genre' column
            $table->string('genre');
        });
    }
};
