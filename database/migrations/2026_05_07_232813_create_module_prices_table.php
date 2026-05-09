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
        Schema::create('module_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('catalog_code')->nullable();
            $table->string('catalog_name')->nullable();

            $table->string('reference')->nullable();
            $table->string('description_base')->nullable();

            $table->decimal('dx', 12, 2)->default(0);
            $table->decimal('dy', 12, 2)->default(0);
            $table->decimal('dz', 12, 2)->default(0);

            $table->string('model')->nullable();

            $table->string('complexity_level')->default('simple');
            $table->decimal('complexity_factor', 8, 2)->default(1.00);

            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->decimal('indirect_cost', 12, 2)->default(0);

            $table->decimal('default_waste_percent', 8, 2)->default(10);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique([
                'company_id',
                'catalog_code',
                'reference',
                'dx',
                'dy',
                'dz',
            ], 'module_price_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_prices');
    }
};
