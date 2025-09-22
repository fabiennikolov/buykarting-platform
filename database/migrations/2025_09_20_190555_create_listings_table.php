<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('condition');
            $table->decimal('price', 8, 2);
            $table->string('currency');
            $table->string('country', 100);
            $table->string('state_province', 100)->nullable();
            $table->string('city', 100);
            $table->enum('status', ['active', 'sold', 'draft'])->default('active');
            $table->timestamps();

            $table->index(['status']);
            $table->index(['country', 'city']);
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
