<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        $hotel = Hotel::first();
        $types = ['standard', 'superior', 'deluxe', 'suite', 'family', 'studio', 'apartment'];
        $names = ['Стандарт', 'Улучшенная', 'Люкс', 'Сьют', 'Семейная', 'Студия', 'Апартаменты'];
        $prices = [2500, 3500, 5000, 7500, 4500, 3000, 6000];

        for ($i = 1; $i <= 20; $i++) {
            $t = array_rand($types);
            Room::create([
                'hotel_id' => $hotel->id,
                'name' => $names[$t] . ' №' . $i,
                'type' => $types[$t],
                'description' => 'Уютная квартира с современным ремонтом и всеми удобствами.',
                'price_per_night' => $prices[$t] + rand(-500, 500),
                'capacity_adults' => rand(1, 4),
                'capacity_children' => rand(0, 2),
                'bed_type' => ['single', 'double', 'queen', 'king'][rand(0, 3)],
                'bed_count' => rand(1, 3),
                'size_sqm' => rand(20, 80),
                'quantity' => rand(1, 5),
                'available_quantity' => rand(1, 5),
                'status' => 'approved',
                'is_active' => true,
                'is_available' => true,
            ]);
        }
    }
}
