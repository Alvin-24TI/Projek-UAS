<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah kolom untuk bukti pembayaran
            if (!Schema::hasColumn('orders', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_method');
            }
            // Tambah kolom untuk status bukti pembayaran
            if (!Schema::hasColumn('orders', 'proof_status')) {
                $table->enum('proof_status', ['pending', 'approved', 'rejected'])->default('pending')->after('payment_proof');
            }
            // Tambah kolom untuk catatan penolakan bukti
            if (!Schema::hasColumn('orders', 'proof_rejection_reason')) {
                $table->text('proof_rejection_reason')->nullable()->after('proof_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
            if (Schema::hasColumn('orders', 'proof_status')) {
                $table->dropColumn('proof_status');
            }
            if (Schema::hasColumn('orders', 'proof_rejection_reason')) {
                $table->dropColumn('proof_rejection_reason');
            }
        });
    }
};
