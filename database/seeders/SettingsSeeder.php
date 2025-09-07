<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'company_name',
                'value' => '8BITS | SUPORTE',
                'description' => 'Nome da empresa',
                'type' => 'text'
            ],
            [
                'key' => 'company_email',
                'value' => 'suporte@8bits.com.br',
                'description' => 'Email da empresa',
                'type' => 'email'
            ],
            [
                'key' => 'audit_views',
                'value' => 'true',
                'description' => 'Habilitar auditoria de visualizações',
                'type' => 'boolean'
            ],
            [
                'key' => 'audit_retention_days',
                'value' => '365',
                'description' => 'Dias para manter logs de auditoria',
                'type' => 'number'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
