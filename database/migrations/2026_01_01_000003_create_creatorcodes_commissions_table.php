<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creatorcodes_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_code_id')->constrained('creatorcodes_codes')->cascadeOnDelete();
            $table->unsignedBigInteger('order_id')->unique();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->decimal('order_amount', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->boolean('paid_out')->default(false);
            $table->timestamp('paid_out_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creatorcodes_commissions');
    }
};
