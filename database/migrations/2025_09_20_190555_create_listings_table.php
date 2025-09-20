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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description');
            $table->enum('category', ['go_kart', 'parts', 'accessories', 'consumables']);
            $table->enum('condition', ['new', 'used']);
            $table->decimal('price', 8, 2);
            $table->string('currency', 3);
            $table->string('country', 100);
            $table->string('state_province', 100)->nullable();
            $table->string('city', 100);
            $table->string('image_path', 500)->nullable();
            $table->enum('status', ['active', 'sold', 'draft'])->default('active');
            $table->timestamps();

            $table->index(['category', 'status']);
            $table->index(['country', 'city']);
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
