@extends('layouts.app')

@section('title', 'Квартиры - ' . $hotel->name)

@section('content')
<div class="min-h-screen bg-white">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl py-12">
        <div class="mb-8">
            <a href="{{ route('hotels.show', $hotel) }}" class="text-[#3B82F6] hover:underline text-sm mb-2 inline-block">← Назад к дому</a>
            <h1 class="text-3xl font-bold text-gray-900">Квартиры в {{ $hotel->name }}</h1>
            <p class="text-gray-500 mt-1">{{ $hotel->city }}, {{ $hotel->country }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ $room->main_image ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800' }}"
                             alt="{{ $room->name }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $room->name }}</h3>
                        <p class="text-sm text-gray-500 mb-3">{{ ucfirst($room->type) }} · {{ $room->capacity_adults }} взр.</p>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <div>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($room->price_per_night, 0, ',', ' ') }} ₽</span>
                                <span class="text-xs text-gray-500">/ ночь</span>
                            </div>
                            <a href="{{ route('rooms.show', $room) }}" class="px-5 py-2 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-gray-800 transition-colors">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <p class="text-xl text-gray-500">Квартиры не найдены</p>
                </div>
            @endforelse
        </div>

        @if($rooms->hasPages())
            <div class="mt-12">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
