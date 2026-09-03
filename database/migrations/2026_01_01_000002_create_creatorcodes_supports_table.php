<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creatorcodes_supports', function (Blueprint $table) {
            $table->id();
            // Un acheteur ne soutient qu'un seul createur a la fois (comme Fortnite)
            // Pas de FK stricte vers `users` (voir migration creatorcodes_codes)
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreignId('creator_code_id')->constrained('creatorcodes_codes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creatorcodes_supports');
    }
};
