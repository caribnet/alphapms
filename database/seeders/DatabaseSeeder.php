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

        // 2. Create Room Type
        $standard = RoomType::create([
            'name' => 'Standard Room',
            'base_rate' => 100.00,
            'capacity' => 2,
            'description' => 'A comfortable standard room.'
        ]);

        // 3. Generate Rooms 101-110
        for ($i = 101; $i <= 110; $i++) {
            Room::create([
                'room_type_id' => $standard->id,
                'room_number' => (string)$i,
                'status' => 'available'
            ]);
        }

        // 4. Generate Rooms 201-210
        for ($i = 201; $i <= 210; $i++) {
            Room::create([
                'room_type_id' => $standard->id,
                'room_number' => (string)$i,
                'status' => 'available'
            ]);
        }

        // 5. Create Wholesaler
        Wholesaler::create([
            'name' => 'Apple Vacations',
            'email' => 'contracts@applevacations.com',
            'contact_person' => 'John Appleseed',
            'commission_rate' => 15.00
        ]);
    }
}
