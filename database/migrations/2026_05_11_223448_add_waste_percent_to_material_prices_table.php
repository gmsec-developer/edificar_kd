<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('material_prices', 'waste_percent')) {
                $table->decimal('waste_percent', 8, 2)->default(0)->after('unit_cost');
            }
        });
    }

    public function down(): void
    {
        Schema::table('material_prices', function (Blueprint $table) {
            if (Schema::hasColumn('material_prices', 'waste_percent')) {
                $table->dropColumn('waste_percent');
            }
        });
    }
};
