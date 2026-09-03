<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creatorcodes_commissions', function (Blueprint $table) {
            $table->string('currency', 8)->default('EUR')->after('commission_amount');
            $table->string('paypal_batch_id')->nullable()->after('paid_out_at');
            $table->string('paypal_status')->nullable()->after('paypal_batch_id');
            $table->text('paypal_error')->nullable()->after('paypal_status');
        });
    }

    public function down(): void
    {
        Schema::table('creatorcodes_commissions', function (Blueprint $table) {
            $table->dropColumn(['currency', 'paypal_batch_id', 'paypal_status', 'paypal_error']);
        });
    }
};
