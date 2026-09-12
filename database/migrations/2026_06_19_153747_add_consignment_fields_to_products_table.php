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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_consignment')->default(false)->after('selling_price');
            $table->unsignedBigInteger('bo_id')->nullable()->after('is_consignment');
            $table->foreign('bo_id')->references('id')->on('bo')->nullOnDelete();
        });

        Schema::table('bo_orders', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bo_orders', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['bo_id']);
            $table->dropColumn(['is_consignment', 'bo_id']);
        });
    }
};
