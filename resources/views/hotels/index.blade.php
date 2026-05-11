@extends('layouts.app')

@section('title', 'Поиск домов')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero секция -->
    <div class="relative overflow-hidden bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4 lg:px-8 py-12 lg:py-20 max-w-6xl">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 tracking-tight leading-tight">
                    Отдыхайте <span class="text-[#3B82F6]">выгоднее</span>
                </h1>
                <p class="text-base md:text-lg text-gray-500 max-w-2xl mx-auto">
                    Будьте как дома
                </p>
            </div>

            <!-- Поисковая форма -->
            <form action="{{ route('hotels.index') }}" method="GET" class="max-w-4xl mx-auto mb-10">
                <div class="bg-white rounded-2xl shadow-lg p-2 flex flex-col md:flex-row gap-2">
                    <div class="flex-1">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Название дома или город..."
                               class="w-full px-6 py-4 rounded-xl border-0 focus:ring-2 focus:ring-gray-200 transition-all">
                    </div>
                    <div class="md:w-48">
                        <select name="stars" class="w-full px-6 py-4 rounded-xl border-0 focus:ring-2 focus:ring-gray-200 transition-all">
                            <option value="">Все звезды</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('stars') == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="px-8 py-4 bg-gray-900 text-white rounded-xl font-medium hover:bg-gray-800 transition-all flex items-center justify-center gap-2">
                        <span>Найти</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>


        </div>
    </div>

    <!-- Список домов -->
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl py-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Найдено домов: {{ $hotels->total() }}</h2>
                <p class="text-gray-500 mt-1">Выберите подходящий вариант для вашего отдыха</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($hotels as $hotel)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 card-hover">
                    <div class="h-56 overflow-hidden relative">
                        <img src="{{ $hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800' }}"
                             alt="{{ $hotel->name }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        @if($hotel->is_featured)
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                                Рекомендуем
                            </span>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900 text-xl">{{ $hotel->name }}</h3>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $hotel->stars ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $hotel->city }}, {{ $hotel->country }}</span>
                        </div>
                        @if($hotel->short_description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $hotel->short_description }}
                            </p>
                        @endif
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($hotel->min_price, 0, ',', ' ') }} ₽</div>
                                <div class="text-xs text-gray-500">за ночь</div>
                            </div>
                            <a href="{{ route('hotels.show', $hotel) }}" class="px-6 py-3 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-gray-800 transition-colors">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-xl text-gray-500 mb-2">Квартиры не найдены</p>
                    <p class="text-sm text-gray-400">Попробуйте изменить параметры поиска</p>
                </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        @if($hotels->hasPages())
            <div class="mt-12">
                {{ $hotels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
