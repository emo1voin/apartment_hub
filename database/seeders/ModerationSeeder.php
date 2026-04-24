<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class ModerationSeeder extends Seeder
{
    public function run(): void
    {
        $cities = ['Москва', 'Сочи', 'Казань', 'Санкт-Петербург', 'Екатеринбург', 'Новосибирск', 'Ялта', 'Калининград', 'Владивосток', 'Ростов-на-Дону', 'Самара', 'Пятигорск', 'Иркутск', 'Мурманск', 'Анапа'];

        for ($i = 1; $i <= 15; $i++) {
            $city = $cities[array_rand($cities)];
            Hotel::create([
                'name' => 'Дом на модерации #' . $i,
                'description' => 'Описание дома на модерации номер ' . $i,
                'short_description' => 'Дом ожидает проверки администратором',
                'address' => 'ул. Тестовая, ' . rand(1, 100),
                'city' => $city,
                'country' => 'Россия',
                'stars' => rand(2, 5),
                'owner_id' => 2,
                'status' => 'pending',
                'is_active' => false,
            ]);
        }

        $approvedHotels = Hotel::where('status', 'approved')->pluck('id')->toArray();

        for ($i = 1; $i <= 15; $i++) {
            $hotelId = $approvedHotels[array_rand($approvedHotels)];
            $types = ['standard', 'superior', 'deluxe', 'suite', 'family', 'studio', 'apartment'];
            $type = $types[array_rand($types)];

            Room::create([
                'hotel_id' => $hotelId,
                'name' => 'Квартира на модерации #' . $i,
                'description' => 'Квартира ожидает проверки',
                'type' => $type,
                'price_per_night' => rand(2000, 8000),
                'capacity_adults' => rand(1, 4),
                'capacity_children' => rand(0, 2),
                'bed_type' => ['single', 'double', 'queen', 'king'][rand(0, 3)],
                'bed_count' => rand(1, 3),
                'size_sqm' => rand(20, 80),
                'quantity' => 1,
                'available_quantity' => 1,
                'status' => 'pending',
                'is_active' => false,
                'is_available' => false,
            ]);
        }
    }
}
