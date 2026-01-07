<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add description to categories if table exists and column doesn't exist
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'description')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('description')->nullable()->after('slug');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'description')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
