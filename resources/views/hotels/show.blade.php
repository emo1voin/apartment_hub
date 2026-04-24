@extends('layouts.app')

@section('title', $hotel->name . ' - ApartmentHub')

@section('content')
<div class="min-h-screen bg-white">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl py-8">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('hotels.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Назад к домам
                </a>
                
                @if($apiAuth->check() && $apiAuth->isAdmin())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.hotels.edit', $hotel) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Редактировать
                            </a>
                            <a href="{{ route('admin.rooms.create', $hotel) }}" 
                               class="inline-flex items-center px-4 py-2 bg-[#3B82F6] hover:bg-[#2563EB] text-white rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Добавить квартиру
                            </a>
                            <form action="{{ route('admin.hotels.destroy', $hotel) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Вы уверены, что хотите удалить этот дом? Все квартиры и бронирования также будут удалены.');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Удалить
                                </button>
                            </form>
                        </div>
                    @endif
            </div>
        </div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Главное изображение -->
    @if($hotel->main_image)
        <div class="w-full h-96 overflow-hidden rounded-t-2xl">
            <img src="{{ asset($hotel->main_image) }}" 
                 alt="{{ $hotel->name }}" 
                 class="w-full h-full object-cover">
        </div>
    @endif
    
    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $hotel->name }}</h1>
                <div class="flex items-center gap-2 mb-4">
                    @for($i = 0; $i < $hotel->stars; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            </div>
            @if($hotel->rating > 0)
                <div class="bg-[#3B82F6] text-white px-6 py-3 rounded-xl text-center">
                    <div class="text-3xl font-bold">{{ $hotel->rating }}</div>
                    <div class="text-sm opacity-90">из 5</div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Адрес</p>
                        <p class="text-gray-600">{{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->country }}</p>
                    </div>
                </div>
                @if($hotel->phone)
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Телефон</p>
                        <p class="text-gray-600">{{ $hotel->phone }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="space-y-3">
                @if($hotel->email)
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Email</p>
                        <p class="text-gray-600">{{ $hotel->email }}</p>
                    </div>
                </div>
                @endif
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Время заезда/выезда</p>
                        <p class="text-gray-600">Заезд: {{ $hotel->check_in_time }} | Выезд: {{ $hotel->check_out_time }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($hotel->description)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Описание</h2>
            <p class="text-gray-600 leading-relaxed">{{ $hotel->description }}</p>
        </div>
        @endif

        @if($hotel->amenities->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Удобства дома</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($hotel->amenities as $amenity)
                    <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-[#3B82F6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $amenity->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Доступные квартиры</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($rooms as $room)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="h-48 overflow-hidden">
                    @if($room->main_image)
                        <img src="{{ asset($room->main_image) }}" 
                             alt="{{ $room->name }}" 
                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $room->name }}</h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $room->capacity_adults }} взрослых, {{ $room->capacity_children }} детей</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>{{ ucfirst($room->bed_type) }} ({{ $room->bed_count }} шт.)</span>
                        </div>
                        @if($room->size_sqm)
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            <span>{{ $room->size_sqm }} м²</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div>
                            <div class="text-2xl font-bold text-[#3B82F6]">{{ number_format($room->price_per_night, 0, ',', ' ') }} ₽</div>
                            <div class="text-xs text-gray-500">за ночь</div>
                        </div>
                    </div>

                    @if($room->amenities->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Удобства:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($room->amenities->take(3) as $amenity)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $amenity->name }}</span>
                            @endforeach
                            @if($room->amenities->count() > 3)
                                <span class="text-xs text-gray-500 px-2 py-1">+{{ $room->amenities->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="mt-4 space-y-2">
                        <a href="{{ route('rooms.show', $room) }}" class="block w-full text-center py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl font-medium transition-colors">
                            Подробнее
                        </a>
                        @if($apiAuth->check())
                            <a href="{{ route('bookings.create', ['room_id' => $room->id, 'check_in' => now()->addDay()->format('Y-m-d'), 'check_out' => now()->addDays(2)->format('Y-m-d')]) }}" 
                               class="block w-full text-center py-3 bg-[#3B82F6] hover:bg-[#2563EB] text-white rounded-xl font-medium transition-colors">
                                Забронировать
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-[#3B82F6] hover:bg-[#2563EB] text-white rounded-xl font-medium transition-colors">
                                Войти для бронирования
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <p class="text-lg text-gray-500">В данном доме нет доступных квартир</p>
            </div>
        @endforelse
    </div>

    @if($rooms->hasPages())
        <div class="mt-8">
            {{ $rooms->links() }}
        </div>
    @endif

    <!-- Раздел отзывов -->
    @include('partials.reviews')
</div>
</div>
</div>
@endsection
