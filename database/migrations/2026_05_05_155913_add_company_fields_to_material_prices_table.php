<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('material_prices', 'material_code')) {
                $table->string('material_code', 50)->nullable();
            }

            if (!Schema::hasColumn('material_prices', 'material_name')) {
                $table->string('material_name')->nullable();
            }

            if (!Schema::hasColumn('material_prices', 'color_code')) {
                $table->string('color_code', 50)->nullable();
            }

            if (!Schema::hasColumn('material_prices', 'color_name')) {
                $table->string('color_name')->nullable();
            }

            if (!Schema::hasColumn('material_prices', 'unit_type')) {
                $table->string('unit_type', 30)->default('m2');
            }

            if (!Schema::hasColumn('material_prices', 'unit_cost')) {
                $table->decimal('unit_cost', 12, 4)->default(0);
            }

            if (!Schema::hasColumn('material_prices', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('material_prices', function (Blueprint $table) {
            foreach (['material_code', 'material_name', 'color_code', 'color_name', 'unit_type', 'unit_cost', 'is_active'] as $column) {
                if (Schema::hasColumn('material_prices', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
