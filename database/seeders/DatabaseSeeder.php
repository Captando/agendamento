<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\BusinessHour;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo provider
        $provider = User::create([
            'name' => 'Joao Barbeiro',
            'email' => 'joao@demo.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Provider,
            'slug' => 'joao-barbeiro',
            'timezone' => 'America/Sao_Paulo',
        ]);

        // Create demo client
        User::create([
            'name' => 'Maria Cliente',
            'email' => 'maria@demo.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Client,
            'timezone' => 'America/Sao_Paulo',
        ]);

        // Create services
        Service::create([
            'provider_id' => $provider->id,
            'name' => 'Corte de Cabelo',
            'description' => 'Corte masculino tradicional',
            'duration_minutes' => 30,
            'price_cents' => 5000, // R$ 50,00
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Service::create([
            'provider_id' => $provider->id,
            'name' => 'Barba',
            'description' => 'Barba completa com toalha quente',
            'duration_minutes' => 20,
            'price_cents' => 3500, // R$ 35,00
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Service::create([
            'provider_id' => $provider->id,
            'name' => 'Corte + Barba',
            'description' => 'Combo corte e barba',
            'duration_minutes' => 45,
            'price_cents' => 7500, // R$ 75,00
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Service::create([
            'provider_id' => $provider->id,
            'name' => 'Coloracao',
            'description' => 'Coloracao completa',
            'duration_minutes' => 120,
            'price_cents' => 15000, // R$ 150,00
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // Create business hours (Monday-Saturday, 09:00-18:00, break 12:00-13:00)
        for ($day = 1; $day <= 6; $day++) {
            BusinessHour::create([
                'provider_id' => $provider->id,
                'day_of_week' => $day,
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_start' => '12:00',
                'break_end' => '13:00',
                'is_active' => true,
            ]);
        }
    }
}
