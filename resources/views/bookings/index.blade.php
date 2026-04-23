@extends('layouts.app')

@section('title', 'Мои бронирования')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Мои бронирования</h1>

        @if($bookings->count() > 0)
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    @if($booking->hotel && $booking->room)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row">
                            <!-- Изображение -->
                            <div class="md:w-64 h-48 md:h-auto">
                                @if($booking->room->main_image)
                                    <img src="{{ asset($booking->room->main_image) }}" 
                                         alt="{{ $booking->room->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Информация -->
                            <div class="flex-1 p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-1">
                                            <a href="{{ route('hotels.show', $booking->hotel) }}" class="hover:text-[#3B82F6]">
                                                {{ $booking->hotel->name }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600">{{ $booking->room->name }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $booking->hotel->city }}, {{ $booking->hotel->country }}</p>
                                    </div>
                                    <div>
                                        @if($booking->status === 'confirmed')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">Подтверждено</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Отменено</span>
                                        @elseif($booking->status === 'completed')
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">Завершено</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">Заезд</div>
                                        <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in)->format('d.m.Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">Выезд</div>
                                        <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->check_out)->format('d.m.Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">Гости</div>
                                        <div class="font-medium text-gray-900">{{ $booking->adults }} взр., {{ $booking->children }} дет.</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">Ночей</div>
                                        <div class="font-medium text-gray-900">{{ $booking->nights }}</div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                    <div>
                                        <div class="text-2xl font-bold text-gray-900">{{ number_format($booking->total_price, 0, ',', ' ') }} ₽</div>
                                        <div class="text-xs text-gray-500">Общая стоимость</div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('bookings.show', $booking) }}" 
                                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-medium transition-colors">
                                            Подробнее
                                        </a>
                                        @if($booking->canBeCancelled())
                                            <form action="{{ route('bookings.cancel', $booking) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Вы уверены, что хотите отменить бронирование?');">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                                                    Отменить
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Пагинация -->
            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">У вас пока нет бронирований</h3>
                <p class="text-gray-500 mb-6">Начните планировать свое путешествие прямо сейчас</p>
                <a href="{{ route('hotels.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#3B82F6] hover:bg-[#2563EB] text-white rounded-xl font-medium transition-colors">
                    Найти квартиру
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
