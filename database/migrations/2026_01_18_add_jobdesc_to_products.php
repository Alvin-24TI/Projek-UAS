<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambah kolom untuk detail job description yang lebih panjang
            if (!Schema::hasColumn('products', 'jobdesc')) {
                $table->longText('jobdesc')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'jobdesc')) {
                $table->dropColumn('jobdesc');
            }
        });
    }
};
