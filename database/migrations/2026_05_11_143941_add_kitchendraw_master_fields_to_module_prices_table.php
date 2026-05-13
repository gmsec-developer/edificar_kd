<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('module_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('module_prices', 'type_code')) {
                $table->string('type_code')->nullable()->after('catalog_name');
            }

            if (!Schema::hasColumn('module_prices', 'side')) {
                $table->string('side', 5)->nullable()->after('reference');
            }

            if (!Schema::hasColumn('module_prices', 'source')) {
                $table->string('source')->default('manual')->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('module_prices', function (Blueprint $table) {
            if (Schema::hasColumn('module_prices', 'source')) {
                $table->dropColumn('source');
            }

            if (Schema::hasColumn('module_prices', 'side')) {
                $table->dropColumn('side');
            }

            if (Schema::hasColumn('module_prices', 'type_code')) {
                $table->dropColumn('type_code');
            }
        });
    }
};
