@extends('layouts.app')

@section('title', 'Модерация')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <h1 class="text-3xl font-light text-gray-900 mb-8">Панель модерации</h1>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Дома на модерации -->
        <div class="mb-12">
            <h2 class="text-2xl font-medium text-gray-900 mb-6">Дома на модерации ({{ $pendingHotels->count() }})</h2>
            
            @forelse($pendingHotels as $hotel)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $hotel->name }}</h3>
                            <p class="text-gray-600 mb-2">{{ $hotel->city }}, {{ $hotel->country }}</p>
                            <p class="text-sm text-gray-500 mb-2">Владелец: {{ $hotel->owner ? $hotel->owner->name : 'Не указан' }} ({{ $hotel->owner ? $hotel->owner->email : '-' }})</p>
                            <p class="text-gray-700 mb-4">{{ Str::limit($hotel->description, 200) }}</p>
                            <p class="text-sm text-gray-500">Создано: {{ $hotel->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="ml-6 flex flex-col gap-2">
                            <form action="{{ route('admin.moderation.hotels.approve', $hotel) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-colors">
                                    Одобрить
                                </button>
                            </form>
                            <button onclick="showRejectModal('hotel', {{ $hotel->id }})" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                                Отклонить
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Нет домов на модерации</p>
            @endforelse
        </div>

        <!-- Квартиры на модерации -->
        <div>
            <h2 class="text-2xl font-medium text-gray-900 mb-6">Квартиры на модерации ({{ $pendingRooms->count() }})</h2>
            
            @forelse($pendingRooms as $room)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $room->name }}</h3>
                            <p class="text-gray-600 mb-2">Дом: {{ $room->hotel->name }}</p>
                            <p class="text-sm text-gray-500 mb-2">Владелец: {{ $room->hotel->owner ? $room->hotel->owner->name : 'Не указан' }} ({{ $room->hotel->owner ? $room->hotel->owner->email : '-' }})</p>
                            <p class="text-gray-700 mb-2">{{ Str::limit($room->description, 200) }}</p>
                            <div class="flex gap-4 text-sm text-gray-600 mb-4">
                                <span>Вместимость: {{ $room->capacity_adults }} взрослых</span>
                                <span>Цена: {{ number_format($room->price_per_night, 0, '.', ' ') }} ₽/ночь</span>
                                <span>Количество: {{ $room->quantity }}</span>
                            </div>
                            <p class="text-sm text-gray-500">Создано: {{ $room->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="ml-6 flex flex-col gap-2">
                            <form action="{{ route('admin.moderation.rooms.approve', $room) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-colors">
                                    Одобрить
                                </button>
                            </form>
                            <button onclick="showRejectModal('room', {{ $room->id }})" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                                Отклонить
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Нет квартир на модерации</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Модальное окно отклонения -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Причина отклонения</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" rows="4" required
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 mb-4"
                      placeholder="Укажите причину отклонения..."></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                    Отклонить
                </button>
                <button type="button" onclick="hideRejectModal()" class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors">
                    Отмена
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(type, id) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    
    if (type === 'hotel') {
        form.action = `/admin/moderation/hotels/${id}/reject`;
    } else {
        form.action = `/admin/moderation/rooms/${id}/reject`;
    }
    
    modal.classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
