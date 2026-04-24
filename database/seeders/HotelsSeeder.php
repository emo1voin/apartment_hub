<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class HotelsSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            ['name' => 'Солнечный берег', 'city' => 'Сочи', 'country' => 'Россия', 'address' => 'ул. Приморская, 15', 'stars' => 5, 'short_description' => 'Роскошный дом с видом на море'],
            ['name' => 'Уютный дворик', 'city' => 'Москва', 'country' => 'Россия', 'address' => 'ул. Арбат, 42', 'stars' => 4, 'short_description' => 'Тихий дом в центре столицы'],
            ['name' => 'Невский причал', 'city' => 'Санкт-Петербург', 'country' => 'Россия', 'address' => 'Невский пр., 88', 'stars' => 5, 'short_description' => 'Элегантный дом на Невском'],
            ['name' => 'Казанская жемчужина', 'city' => 'Казань', 'country' => 'Россия', 'address' => 'ул. Баумана, 33', 'stars' => 4, 'short_description' => 'Современный дом в историческом центре'],
            ['name' => 'Горный орёл', 'city' => 'Красная Поляна', 'country' => 'Россия', 'address' => 'ул. Горная, 7', 'stars' => 5, 'short_description' => 'Шале в горах с панорамным видом'],
            ['name' => 'Байкальский бриз', 'city' => 'Иркутск', 'country' => 'Россия', 'address' => 'ул. Озёрная, 21', 'stars' => 3, 'short_description' => 'Уютный дом рядом с Байкалом'],
            ['name' => 'Золотое кольцо', 'city' => 'Суздаль', 'country' => 'Россия', 'address' => 'ул. Кремлёвская, 5', 'stars' => 4, 'short_description' => 'Дом в русском стиле'],
            ['name' => 'Морской бриз', 'city' => 'Калининград', 'country' => 'Россия', 'address' => 'ул. Балтийская, 12', 'stars' => 3, 'short_description' => 'Дом у Балтийского моря'],
            ['name' => 'Тихая гавань', 'city' => 'Владивосток', 'country' => 'Россия', 'address' => 'ул. Набережная, 45', 'stars' => 4, 'short_description' => 'Дом с видом на бухту'],
            ['name' => 'Сибирский уют', 'city' => 'Новосибирск', 'country' => 'Россия', 'address' => 'ул. Красный пр., 100', 'stars' => 3, 'short_description' => 'Комфортный дом в центре Сибири'],
            ['name' => 'Кавказский двор', 'city' => 'Пятигорск', 'country' => 'Россия', 'address' => 'ул. Лермонтова, 8', 'stars' => 4, 'short_description' => 'Дом у подножия Машука'],
            ['name' => 'Северное сияние', 'city' => 'Мурманск', 'country' => 'Россия', 'address' => 'ул. Полярная, 3', 'stars' => 3, 'short_description' => 'Дом за полярным кругом'],
            ['name' => 'Волжский закат', 'city' => 'Нижний Новгород', 'country' => 'Россия', 'address' => 'ул. Рождественская, 19', 'stars' => 4, 'short_description' => 'Дом с видом на Волгу'],
            ['name' => 'Уральский камень', 'city' => 'Екатеринбург', 'country' => 'Россия', 'address' => 'ул. Ленина, 55', 'stars' => 5, 'short_description' => 'Премиум дом на Урале'],
            ['name' => 'Крымская роза', 'city' => 'Ялта', 'country' => 'Россия', 'address' => 'ул. Набережная, 30', 'stars' => 5, 'short_description' => 'Дом на южном берегу Крыма'],
            ['name' => 'Донской атаман', 'city' => 'Ростов-на-Дону', 'country' => 'Россия', 'address' => 'ул. Большая Садовая, 77', 'stars' => 3, 'short_description' => 'Гостеприимный дом на Дону'],
            ['name' => 'Алтайский кедр', 'city' => 'Горно-Алтайск', 'country' => 'Россия', 'address' => 'ул. Горная, 14', 'stars' => 4, 'short_description' => 'Экодом в горах Алтая'],
            ['name' => 'Самарская лука', 'city' => 'Самара', 'country' => 'Россия', 'address' => 'ул. Куйбышева, 66', 'stars' => 3, 'short_description' => 'Дом на берегу Волги'],
            ['name' => 'Петергофский дворец', 'city' => 'Санкт-Петербург', 'country' => 'Россия', 'address' => 'Петергофское шоссе, 10', 'stars' => 5, 'short_description' => 'Роскошный дом рядом с фонтанами'],
            ['name' => 'Черноморская звезда', 'city' => 'Анапа', 'country' => 'Россия', 'address' => 'ул. Пионерский пр., 25', 'stars' => 4, 'short_description' => 'Семейный дом у моря'],
        ];

        $roomTypes = ['standard', 'superior', 'deluxe', 'suite', 'family', 'studio', 'apartment'];
        $roomNames = ['Стандарт', 'Улучшенная', 'Люкс', 'Сьют', 'Семейная', 'Студия', 'Апартаменты'];
        $prices = [2500, 3500, 5000, 7500, 4500, 3000, 6000];

        foreach ($hotels as $hotelData) {
            $hotel = Hotel::create([
                ...$hotelData,
                'description' => $hotelData['short_description'] . '. Прекрасное расположение, современный ремонт, все удобства для комфортного проживания.',
                'owner_id' => 1,
                'status' => 'approved',
                'is_active' => true,
                'is_approved' => true,
                'rating' => rand(35, 50) / 10,
            ]);

            // 2-4 квартиры на дом
            $roomCount = rand(2, 4);
            for ($i = 0; $i < $roomCount; $i++) {
                $typeIndex = array_rand($roomTypes);
                Room::create([
                    'hotel_id' => $hotel->id,
                    'name' => $roomNames[$typeIndex] . ' №' . ($i + 1),
                    'type' => $roomTypes[$typeIndex],
                    'description' => 'Уютная квартира с современным ремонтом.',
                    'price_per_night' => $prices[$typeIndex] + rand(-500, 500),
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
}
