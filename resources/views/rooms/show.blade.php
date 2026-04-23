@extends('layouts.app')

@section('title', $room->name)

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            <!-- Хлебные крошки и кнопки управления -->
            <div class="flex items-center justify-between mb-6">
                <nav class="flex text-sm">
                    <a href="{{ route('hotels.index') }}" class="text-gray-500 hover:text-gray-700">Квартиры</a>
                    <span class="mx-2 text-gray-400">/</span>
                    <a href="{{ route('hotels.show', $room->hotel) }}" class="text-gray-500 hover:text-gray-700">{{ $room->hotel->name }}</a>
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-900">{{ $room->name }}</span>
                </nav>

                @if($apiAuth->check() && $apiAuth->isAdmin())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.rooms.edit', $room) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Редактировать
                            </a>
                            <form action="{{ route('admin.rooms.destroy', $room) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Вы уверены, что хотите удалить эту квартиру?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Удалить
                                </button>
                            </form>
                        </div>
                    @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Левая колонка: информация о квартире -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Главное изображение -->
                    @if($room->main_image)
                        <div class="w-full h-96 rounded-2xl overflow-hidden">
                            <img src="{{ asset($room->main_image) }}" 
                                 alt="{{ $room->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endif

                    <!-- Название и основная информация -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $room->name }}</h1>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-[#3B82F6] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div class="text-sm text-gray-500">Вместимость</div>
                                <div class="font-bold text-gray-900">{{ $room->capacity_adults }} + {{ $room->capacity_children }}</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-[#3B82F6] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <div class="text-sm text-gray-500">Кровати</div>
                                <div class="font-bold text-gray-900">{{ $room->bed_count }} шт</div>
                            </div>
                            @if($room->size_sqm)
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-[#3B82F6] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                                <div class="text-sm text-gray-500">Площадь</div>
                                <div class="font-bold text-gray-900">{{ $room->size_sqm }} м²</div>
                            </div>
                            @endif
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <svg class="w-6 h-6 mx-auto text-[#3B82F6] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <div class="text-sm text-gray-500">Тип</div>
                                <div class="font-bold text-gray-900">{{ ucfirst($room->bed_type) }}</div>
                            </div>
                        </div>

                        @if($room->description)
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-3">Описание</h2>
                            <p class="text-gray-600 leading-relaxed">{{ $room->description }}</p>
                        </div>
                        @endif

                        @if($room->amenities->count() > 0)
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-3">Удобства</h2>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($room->amenities as $amenity)
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <svg class="w-5 h-5 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $amenity->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Информация об отеле -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">О доме</h2>
                        <div class="flex items-start gap-4">
                            @if($room->hotel->main_image)
                                <img src="{{ asset($room->hotel->main_image) }}" 
                                     alt="{{ $room->hotel->name }}" 
                                     class="w-24 h-24 rounded-xl object-cover">
                            @endif
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 mb-2">
                                    <a href="{{ route('hotels.show', $room->hotel) }}" class="hover:text-[#3B82F6]">
                                        {{ $room->hotel->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $room->hotel->address }}, {{ $room->hotel->city }}</p>
                                <div class="flex items-center gap-2">
                                    @for($i = 0; $i < $room->hotel->stars; $i++)
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Правая колонка: бронирование -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-24">
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold text-[#3B82F6]">{{ number_format($room->price_per_night, 0, ',', ' ') }} ₽</div>
                            <div class="text-sm text-gray-500">за ночь</div>
                        </div>

                        @if($apiAuth->check())
                            <a href="{{ route('bookings.create', ['room_id' => $room->id, 'check_in' => now()->addDay()->format('Y-m-d'), 'check_out' => now()->addDays(2)->format('Y-m-d')]) }}" 
                               class="block w-full text-center py-4 bg-[#3B82F6] hover:bg-[#2563EB] text-white rounded-xl font-medium transition-colors mb-4">
                                Забронировать
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center py-4 bg-[#3B82F6] hover:bg-[#2563EB] text-white rounded-xl font-medium transition-colors mb-4">
                                Войти для бронирования
                            </a>
                        @endif

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Заезд</span>
                                <span class="text-gray-900 font-medium">{{ $room->hotel->check_in_time }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Выезд</span>
                                <span class="text-gray-900 font-medium">{{ $room->hotel->check_out_time }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <p class="text-xs text-gray-500 text-center">
                                Бесплатная отмена за 24 часа до заезда
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
