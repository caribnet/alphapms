<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Wholesaler;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@pms.com',
            'password' => Hash::make('admin123'),
        ]);

        // 2. Create Room Types
        $standard = RoomType::create([
            'name' => 'Standard Room',
            'base_rate' => 100.00,
            'capacity' => 2,
            'description' => 'A comfortable standard room.'
        ]);

        $suite = RoomType::create([
            'name' => 'Suite',
            'base_rate' => 250.00,
            'capacity' => 4,
            'description' => 'A luxurious suite with a view.'
        ]);

        // 3. Generate Rooms 101-110 (Standard)
        for ($i = 101; $i <= 110; $i++) {
            Room::create([
                'room_type_id' => $standard->id,
                'room_number' => (string)$i,
                'status' => 'available'
            ]);
        }

        // 4. Generate Rooms 201-210 (Suite)
        for ($i = 201; $i <= 210; $i++) {
            Room::create([
                'room_type_id' => $suite->id,
                'room_number' => (string)$i,
                'status' => 'available'
            ]);
        }

        // 5. Create Wholesaler
        $apple = Wholesaler::create([
            'name' => 'Apple Vacations',
            'email' => 'contracts@applevacations.com',
            'contact_person' => 'John Appleseed',
            'commission_rate' => 15.00
        ]);

        // 6. Create Wholesaler Rates (e.g. Special Peak Season Rate)
        \App\Models\WholesalerRate::create([
            'wholesaler_id' => $apple->id,
            'room_type_id' => $suite->id,
            'start_date' => '2026-06-01',
            'end_date' => '2026-08-31',
            'rate' => 220.00 // Discounted rate for the wholesaler during summer
        ]);
    }
}
