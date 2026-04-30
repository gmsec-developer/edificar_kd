<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

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

        User::whereNull('company_id')->update([
            'company_id' => $company->id,
        ]);

        User::whereNull('status')->update([
            'status' => 'active',
        ]);

        User::where('status', 'pending')->update([
            'status' => 'active',
        ]);

        User::whereNull('approved_at')->update([
            'approved_at' => now(),
        ]);
    }
}