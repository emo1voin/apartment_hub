@extends('layouts.app')

@section('title', 'Мои квартиры')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-light text-gray-900">Мои квартиры</h1>
            <a href="{{ route('user.rooms.create') }}" class="px-6 py-3 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white rounded-xl transition-all">
                Добавить квартиру
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid gap-6">
            @forelse($rooms as $room)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $room->name }}</h3>
                                @if($room->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">На модерации</span>
                                @elseif($room->status === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">Одобрено</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Отклонено</span>
                                @endif
                            </div>
                            <p class="text-gray-600 mb-2">Дом: {{ $room->hotel->name }}</p>
                            <p class="text-gray-700 mb-4">{{ Str::limit($room->description, 200) }}</p>
                            
                            <div class="flex gap-6 text-sm text-gray-600 mb-4">
                                <span>Тип: {{ $room->type }}</span>
                                <span>Вместимость: {{ $room->capacity_adults }} взрослых</span>
                                <span>Цена: {{ number_format($room->price_per_night, 0, '.', ' ') }} ₽/ночь</span>
                                <span>Количество: {{ $room->quantity }}</span>
                            </div>
                            
                            @if($room->status === 'rejected' && $room->rejection_reason)
                                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4">
                                    <p class="font-medium mb-1">Причина отклонения:</p>
                                    <p>{{ $room->rejection_reason }}</p>
                                </div>
                            @endif

                            <p class="text-sm text-gray-500">Создано: {{ $room->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="ml-6 flex flex-col gap-2">
                            <a href="{{ route('user.rooms.edit', $room) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors text-center">
                                Редактировать
                            </a>
                            <form action="{{ route('user.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Удалить квартиру?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-gray-500 mb-4">У вас пока нет квартир</p>
                    <a href="{{ route('user.rooms.create') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white rounded-xl transition-all">
                        Добавить первую квартиру
                    </a>
                </div>
            @endforelse
        </div>

        @if($rooms->hasPages())
            <div class="mt-8">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
