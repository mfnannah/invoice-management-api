<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);
        if ($user) {
            $tenant = Company::create([
                'name' => 'Test Company',
                'owner_id' => $user->id,
            ]);
            User::where('id', $user->id)->update([
                'tenant_id' => $tenant?->id,
            ]);
        }

    }
}
