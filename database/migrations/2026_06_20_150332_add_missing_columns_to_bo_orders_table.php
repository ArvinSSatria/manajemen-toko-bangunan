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
        Schema::table('bo_orders', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('store_id');
            $table->string('description')->nullable()->after('invoice_number');
            $table->decimal('paid_amount', 15, 2)->default(0)->after('total');
            $table->decimal('remaining_balance', 15, 2)->default(0)->after('paid_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bo_orders', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'description', 'paid_amount', 'remaining_balance']);
        });
    }
};
