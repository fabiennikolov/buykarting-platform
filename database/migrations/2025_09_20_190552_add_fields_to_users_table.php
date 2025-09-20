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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['individual', 'business'])->default('individual')->after('email');
            $table->string('country', 100)->after('type');
            $table->string('state_province', 100)->nullable()->after('country');
            $table->string('city', 100)->after('state_province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'country', 'state_province', 'city']);
        });
    }
};
