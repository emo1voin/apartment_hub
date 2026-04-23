<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Amenity;
use Illuminate\Support\Facades\Hash;

class BookingSystemSeeder extends Seeder
{
    public function run(): void
    {

        $admin = User::create([
            'name' => 'Администратор',
            'email' => 'admin@booking.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);


        $user = User::create([
            'name' => 'Иван Иванов',
            'email' => 'user@booking.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Создаем удобства
        $amenities = [
            ['name' => 'Wi-Fi', 'slug' => 'wifi', 'icon' => 'wifi', 'category' => 'hotel'],
            ['name' => 'Парковка', 'slug' => 'parking', 'icon' => 'car', 'category' => 'hotel'],
            ['name' => 'Бассейн', 'slug' => 'pool', 'icon' => 'swimming-pool', 'category' => 'hotel'],
            ['name' => 'Ресторан', 'slug' => 'restaurant', 'icon' => 'utensils', 'category' => 'hotel'],
            ['name' => 'Спа', 'slug' => 'spa', 'icon' => 'spa', 'category' => 'hotel'],
            ['name' => 'Фитнес-центр', 'slug' => 'gym', 'icon' => 'dumbbell', 'category' => 'hotel'],
            ['name' => 'Кондиционер', 'slug' => 'ac', 'icon' => 'snowflake', 'category' => 'rooms'],
            ['name' => 'Телевизор', 'slug' => 'tv', 'icon' => 'tv', 'category' => 'rooms'],
            ['name' => 'Мини-бар', 'slug' => 'minibar', 'icon' => 'glass-martini', 'category' => 'rooms'],
            ['name' => 'Сейф', 'slug' => 'safe', 'icon' => 'lock', 'category' => 'rooms'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }

        // Создаем отели
        $hotel1 = Hotel::create([
            'name' => 'Гранд Отель Москва',
            'description' => 'Роскошный 5-звездочный отель в центре Москвы с видом на Кремль',
            'short_description' => 'Роскошный отель в центре Москвы',
            'address' => 'Тверская улица, 1',
            'city' => 'Москва',
            'country' => 'Россия',
            'postal_code' => '125009',
            'phone' => '+7 (495) 123-45-67',
            'email' => 'info@grandhotel.ru',
            'stars' => 5,
            'rating' => 4.8,
            'min_price' => 5000,
            'max_price' => 25000,
            'is_active' => true,
            'is_featured' => true,
            'allows_pets' => false,
            'allows_children' => true,
        ]);

        $hotel2 = Hotel::create([
            'name' => 'Отель Невский',
            'description' => 'Уютный 4-звездочный отель на Невском проспекте',
            'short_description' => 'Уютный отель в Санкт-Петербурге',
            'address' => 'Невский проспект, 50',
            'city' => 'Санкт-Петербург',
            'country' => 'Россия',
            'postal_code' => '191025',
            'phone' => '+7 (812) 123-45-67',
            'email' => 'info@nevsky-hotel.ru',
            'stars' => 4,
            'rating' => 4.5,
            'min_price' => 3000,
            'max_price' => 15000,
            'is_active' => true,
            'is_featured' => false,
            'allows_pets' => true,
            'allows_children' => true,
        ]);

        // Привязываем удобства к отелям
        $hotelAmenities = Amenity::where('category', 'hotel')->pluck('id');
        $hotel1->amenities()->attach($hotelAmenities);
        $hotel2->amenities()->attach($hotelAmenities->take(4));

        // Создаем номера для первого отеля
        $room1 = Room::create([
            'hotel_id' => $hotel1->id,
            'name' => 'Стандартный номер',
            'room_number' => '101',
            'description' => 'Уютный стандартный номер с видом на город',
            'type' => 'standard',
            'capacity_adults' => 2,
            'capacity_children' => 1,
            'total_capacity' => 3,
            'size_sqm' => 25,
            'bed_type' => 'double',
            'bed_count' => 1,
            'price_per_night' => 5000,
            'quantity' => 10,
            'available_quantity' => 10,
            'is_active' => true,
            'is_available' => true,
        ]);

        $room2 = Room::create([
            'hotel_id' => $hotel1->id,
            'name' => 'Люкс',
            'room_number' => '201',
            'description' => 'Роскошный номер люкс с панорамным видом',
            'type' => 'suite',
            'capacity_adults' => 2,
            'capacity_children' => 2,
            'total_capacity' => 4,
            'size_sqm' => 50,
            'bed_type' => 'king',
            'bed_count' => 1,
            'price_per_night' => 15000,
            'quantity' => 5,
            'available_quantity' => 5,
            'is_active' => true,
            'is_available' => true,
        ]);

        // Создаем номера для второго отеля
        $room3 = Room::create([
            'hotel_id' => $hotel2->id,
            'name' => 'Эконом',
            'room_number' => '301',
            'description' => 'Комфортный номер эконом-класса',
            'type' => 'standard',
            'capacity_adults' => 2,
            'capacity_children' => 0,
            'total_capacity' => 2,
            'size_sqm' => 20,
            'bed_type' => 'double',
            'bed_count' => 1,
            'price_per_night' => 3000,
            'quantity' => 15,
            'available_quantity' => 15,
            'is_active' => true,
            'is_available' => true,
        ]);

        // Привязываем удобства к номерам
        $roomAmenities = Amenity::where('category', 'rooms')->pluck('id');
        $room1->amenities()->attach($roomAmenities);
        $room2->amenities()->attach($roomAmenities);
        $room3->amenities()->attach($roomAmenities->take(3));

        $this->command->info('Тестовые данные успешно созданы!');
        $this->command->info('Админ: admin@booking.com / password');
        $this->command->info('Пользователь: user@booking.com / password');
    }
}
