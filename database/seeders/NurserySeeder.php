<?php

namespace Database\Seeders;

use App\Models\Nursery;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NurserySeeder extends Seeder
{
    public function run(): void
    {
        // First Nursery - Little Stars
        $littleStars = Nursery::create([
            'name' => 'Little Stars Nursery',
            'email' => 'admin@littlestars.com',
            'phone' => '01234567890',
            'address' => '123 Star Lane, Starville, ST1 1AB',
        ]);

        // Little Stars Staff
        User::create([
            'nursery_id' => $littleStars->id,
            'name' => 'Sarah Admin',
            'email' => 'sarah@littlestars.com',
            'password' => Hash::make('password'),
            'type' => 'staff',
            'is_admin' => true,
        ]);

        User::create([
            'nursery_id' => $littleStars->id,
            'name' => 'John Teacher',
            'email' => 'john@littlestars.com',
            'password' => Hash::make('password'),
            'type' => 'staff',
            'is_admin' => false,
        ]);

        // Little Stars Parents
        User::create([
            'nursery_id' => $littleStars->id,
            'name' => 'Mike Parent',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'type' => 'parent',
            'is_admin' => false,
        ]);

        // Second Nursery - Sunshine Kids
        $sunshineKids = Nursery::create([
            'name' => 'Sunshine Kids Nursery',
            'email' => 'admin@sunshinekids.com',
            'phone' => '09876543210',
            'address' => '456 Sun Road, Sunnyville, SN1 2CD',
        ]);

        // Sunshine Kids Staff
        User::create([
            'nursery_id' => $sunshineKids->id,
            'name' => 'Emma Admin',
            'email' => 'emma@sunshinekids.com',
            'password' => Hash::make('password'),
            'type' => 'staff',
            'is_admin' => true,
        ]);

        User::create([
            'nursery_id' => $sunshineKids->id,
            'name' => 'Lisa Teacher',
            'email' => 'lisa@sunshinekids.com',
            'password' => Hash::make('password'),
            'type' => 'staff',
            'is_admin' => false,
        ]);

        // Sunshine Kids Parents
        User::create([
            'nursery_id' => $sunshineKids->id,
            'name' => 'Jane Parent',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'type' => 'parent',
            'is_admin' => false,
        ]);
    }
}
