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
        // Only run if table exists (to avoid errors when table doesn't exist yet)
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                // Add shipping and payment columns if they don't exist
                if (!Schema::hasColumn('orders', 'shipping_address')) {
                    $table->text('shipping_address')->nullable()->after('total_amount');
                }
                if (!Schema::hasColumn('orders', 'shipping_phone')) {
                    $table->string('shipping_phone')->nullable()->after('shipping_address');
                }
                if (!Schema::hasColumn('orders', 'shipping_city')) {
                    $table->string('shipping_city')->nullable()->after('shipping_phone');
                }
                if (!Schema::hasColumn('orders', 'shipping_province')) {
                    $table->string('shipping_province')->nullable()->after('shipping_city');
                }
                if (!Schema::hasColumn('orders', 'shipping_zip')) {
                    $table->string('shipping_zip')->nullable()->after('shipping_province');
                }
                if (!Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('shipping_zip');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'shipping_address')) {
                    $table->dropColumn('shipping_address');
                }
                if (Schema::hasColumn('orders', 'shipping_phone')) {
                    $table->dropColumn('shipping_phone');
                }
                if (Schema::hasColumn('orders', 'shipping_city')) {
                    $table->dropColumn('shipping_city');
                }
                if (Schema::hasColumn('orders', 'shipping_province')) {
                    $table->dropColumn('shipping_province');
                }
                if (Schema::hasColumn('orders', 'shipping_zip')) {
                    $table->dropColumn('shipping_zip');
                }
                if (Schema::hasColumn('orders', 'payment_method')) {
                    $table->dropColumn('payment_method');
                }
            });
        }
    }
};
