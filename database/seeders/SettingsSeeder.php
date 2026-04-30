<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'allow_register',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'auth',
                'label' => 'Permitir registro público',
                'description' => 'Activa o desactiva el registro de usuarios',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
