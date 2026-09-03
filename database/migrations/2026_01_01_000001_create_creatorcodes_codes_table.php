<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creatorcodes_codes', function (Blueprint $table) {
            $table->id();
            // Pas de contrainte FK stricte vers `users` : le type de la colonne
            // `id` de la table users peut varier selon la version d'Azuriom.
            // La relation Eloquent (voir CreatorCode::creator()) fonctionne
            // sans contrainte reelle en base.
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->string('code')->unique();
            // Taux de commission en pourcentage (ex: 5.00 = 5%)
            $table->decimal('commission_rate', 5, 2)->default(5.00);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creatorcodes_codes');
    }
};
