@extends('layouts.app')

@section('title', 'Бронирование #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Назад к бронированиям
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Бронирование #{{ $booking->id }}</h1>
                        <p class="text-gray-500">Создано {{ $booking->created_at->format('d.m.Y в H:i') }}</p>
                    </div>
                    <div>
                        @if($booking->status === 'confirmed')
                            <span class="px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">Подтверждено</span>
                        @elseif($booking->status === 'pending')
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">Ожидание</span>
                        @elseif($booking->status === 'cancelled')
                            <span class="px-4 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-full">Отменено</span>
                        @elseif($booking->status === 'completed')
                            <span class="px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">Завершено</span>
                        @endif
                    </div>
                </div>

                <!-- Информация о доме -->
                <div class="mb-8 pb-8 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Информация о доме</h2>
                    <div class="flex gap-4">
                        @if($booking->hotel->main_image)
                            <img src="{{ asset($booking->hotel->main_image) }}" 
                                 alt="{{ $booking->hotel->name }}" 
                                 class="w-32 h-32 rounded-xl object-cover">
                        @endif
                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">
                                <a href="{{ route('hotels.show', $booking->hotel) }}" class="hover:text-[#3B82F6]">
                                    {{ $booking->hotel->name }}
                                </a>
                            </h3>
                            <p class="text-gray-600 mb-1">{{ $booking->hotel->address }}, {{ $booking->hotel->city }}</p>
                            <p class="text-gray-600">{{ $booking->hotel->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Информация о квартире -->
                <div class="mb-8 pb-8 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Информация о квартире</h2>
                    <p class="text-gray-900 font-medium mb-2">{{ $booking->room->name }}</p>
                    <p class="text-gray-600">{{ ucfirst($booking->room->bed_type) }} • {{ $booking->room->bed_count }} кровати</p>
                </div>

                <!-- Даты бронирования -->
                <div class="mb-8 pb-8 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Даты бронирования</h2>
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Заезд</div>
                            <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d.m.Y') }}</div>
                            <div class="text-sm text-gray-500">после {{ $booking->hotel->check_in_time }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Выезд</div>
                            <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d.m.Y') }}</div>
                            <div class="text-sm text-gray-500">до {{ $booking->hotel->check_out_time }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Количество ночей</div>
                            <div class="font-medium text-gray-900">{{ $booking->nights }}</div>
                        </div>
                    </div>
                </div>

                <!-- Гости -->
                <div class="mb-8 pb-8 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Гости</h2>
                    <p class="text-gray-900">Взрослых: {{ $booking->adults }}, Детей: {{ $booking->children }}</p>
                    <p class="text-gray-600">Всего гостей: {{ $booking->total_guests }}</p>
                </div>

                @if($booking->special_requests)
                    <div class="mb-8 pb-8 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Особые пожелания</h2>
                        <p class="text-gray-600">{{ $booking->special_requests }}</p>
                    </div>
                @endif

                <!-- Стоимость -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Стоимость</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>{{ number_format($booking->price_per_night, 0, ',', ' ') }} ₽ × {{ $booking->nights }} ночей</span>
                            <span>{{ number_format($booking->subtotal, 0, ',', ' ') }} ₽</span>
                        </div>
                        @if($booking->tax_amount > 0)
                        <div class="flex justify-between text-gray-600">
                            <span>Налоги</span>
                            <span>{{ number_format($booking->tax_amount, 0, ',', ' ') }} ₽</span>
                        </div>
                        @endif
                        @if($booking->service_fee > 0)
                        <div class="flex justify-between text-gray-600">
                            <span>Сервисный сбор</span>
                            <span>{{ number_format($booking->service_fee, 0, ',', ' ') }} ₽</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-xl font-bold text-gray-900 pt-3 border-t border-gray-200">
                            <span>Итого</span>
                            <span>{{ number_format($booking->total_price, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>

                <!-- Действия -->
                @if($booking->canBeCancelled())
                    <div class="flex justify-end">
                        <form action="{{ route('bookings.cancel', $booking) }}" 
                              method="POST" 
                              onsubmit="return confirm('Вы уверены, что хотите отменить бронирование?');">
                            @csrf
                            <button type="submit" 
                                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors">
                                Отменить бронирование
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
