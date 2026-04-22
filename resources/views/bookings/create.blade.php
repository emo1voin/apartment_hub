@extends('layouts.app')

@section('title', 'Создать бронирование')

@section('content')
    @if ($errors->any())
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                <p class="font-medium">Ошибки валидации:</p>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        </div>
    @endif
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            <!-- Хлебные крошки -->
            <nav class="flex mb-6 text-sm">
                <a href="{{ route('hotels.index') }}" class="text-gray-500 hover:text-gray-700">Квартиры</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('hotels.show', $room->hotel) }}" class="text-gray-500 hover:text-gray-700">{{ $room->hotel->name }}</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('rooms.show', $room) }}" class="text-gray-500 hover:text-gray-700">{{ $room->name }}</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900">Бронирование</span>
            </nav>

            <h1 class="text-3xl font-light text-gray-900 mb-6">Бронирование квартиры</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Левая колонка: информация о доме -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Информация об отеле -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $room->hotel->name }}</h2>
                        <p class="text-gray-600 mb-1">{{ $room->name }}</p>
                        <p class="text-sm text-gray-500">{{ $room->hotel->city }}, {{ $room->hotel->country }}</p>
                    </div>

                    <!-- Форма бронирования -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Детали бронирования</h3>
                        
                        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-5" id="bookingForm">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">

                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl mb-4">
                                <p class="text-sm text-blue-800">
                                    <strong>Отладка:</strong> Форма отправляется на {{ route('bookings.store') }}
                                </p>
                            </div>

                            <!-- Даты -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Дата заезда</label>
                                    <input type="date" name="check_in" value="{{ $checkIn }}" required 
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Дата выезда</label>
                                    <input type="date" name="check_out" value="{{ $checkOut }}" required 
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Гости -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Количество взрослых</label>
                                    <input type="number" name="adults" value="{{ $adults }}" min="1" max="{{ $room->capacity_adults }}" required
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Количество детей</label>
                                    <input type="number" name="children" value="{{ $children }}" min="0" max="{{ $room->capacity_children }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Особые пожелания -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Особые пожелания (необязательно)</label>
                                <textarea name="special_requests" rows="4" 
                                          placeholder="Например: ранний заезд, поздний выезд, дополнительные подушки..."
                                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all resize-none"></textarea>
                            </div>

                            <!-- Кнопка -->
                            <button type="submit" 
                                    class="w-full py-4 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white font-medium rounded-xl transition-all hover:scale-[1.02] shadow-lg text-lg">
                                Подтвердить бронирование
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Правая колонка: детали заказа -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Детали заказа</h3>

                        <!-- Расчет -->
                        <div class="mb-4 p-4 bg-gray-50 rounded-xl">
                            <p class="text-gray-600 mb-2">
                                <span class="font-semibold text-gray-900">{{ number_format($room->price_per_night, 0, '.', ' ') }} ₽</span> × {{ $nights }} ночей
                            </p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($subtotal, 0, '.', ' ') }} ₽</p>
                        </div>

                        <!-- Разбивка -->
                        <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Стоимость проживания</span>
                                <span class="text-gray-900">{{ number_format($subtotal, 0, '.', ' ') }} ₽</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Налоги (10%)</span>
                                <span class="text-gray-900">{{ number_format($taxAmount, 0, '.', ' ') }} ₽</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Сервисный сбор (5%)</span>
                                <span class="text-gray-900">{{ number_format($serviceFee, 0, '.', ' ') }} ₽</span>
                            </div>
                        </div>

                        <!-- Итого -->
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg font-medium text-gray-900">Итого</span>
                            <span class="text-2xl font-bold text-[#3B82F6]">{{ number_format($totalPrice, 0, '.', ' ') }} ₽</span>
                        </div>

                        <!-- Информация -->
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                            <p class="text-sm font-medium text-yellow-900 mb-2">В стоимость входит:</p>
                            <ul class="text-xs text-yellow-800 space-y-1">
                                <li>✓ Бесплатная отмена за 24 часа до заезда</li>
                                <li>✓ Завтрак для всех гостей</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            console.log('=== FORM SUBMIT EVENT ===');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            console.log('Form data:', new FormData(this));
            
            // Показываем данные формы
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
            
            // Не блокируем отправку
            // e.preventDefault();
        });
    </script>
@endsection
