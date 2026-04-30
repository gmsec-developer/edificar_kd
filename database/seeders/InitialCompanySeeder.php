<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InitialCompanySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['code' => 'EDIFICAR'],
            [
                'name' => 'Edificar Empresa Inicial',
                'status' => 'active',
                'max_users' => 10,
            ]
        );

        if (Schema::hasColumn('users', 'company_id')) {
            User::whereNull('company_id')->update([
                'company_id' => $company->id,
            ]);
        }

        if (Schema::hasColumn('users', 'status')) {
            User::whereNull('status')->orWhere('status', '')->update([
                'status' => 'active',
            ]);

            User::query()->update([
                'status' => DB::raw("CASE WHEN status = 'pending' THEN 'active' ELSE status END"),
            ]);
        }

        if (Schema::hasColumn('users', 'approved_at')) {
            User::whereNull('approved_at')->update([
                'approved_at' => now(),
            ]);
        }
    }
}
