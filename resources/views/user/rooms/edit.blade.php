@extends('layouts.app')

@section('title', 'Редактировать квартиру')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-3xl">
        <h1 class="text-3xl font-light text-gray-900 mb-8">Редактировать квартиру</h1>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                <p class="font-medium mb-2">Ошибки:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('user.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Фото квартиры</label>
                    <div class="relative">
                        <input type="file" name="main_image" accept="image/jpeg,image/png,image/jpg,image/webp" id="roomImage"
                               class="hidden" onchange="previewImage(this, 'roomPreview')">
                        <label for="roomImage" class="flex flex-col items-center justify-center w-full h-48 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#3B82F6] hover:bg-blue-50 transition-all">
                            <img id="roomPreview" src="{{ $room->main_image ? asset($room->main_image) : '' }}" class="{{ $room->main_image ? '' : 'hidden' }} w-full h-full object-cover rounded-xl">
                            <div id="roomPlaceholder" class="{{ $room->main_image ? 'hidden' : '' }} text-center">
                                <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-500">Нажмите для загрузки фото</p>
                                <p class="text-xs text-gray-400 mt-1">JPEG, PNG, WebP до 5 МБ</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Выберите дом *</label>
                    <select name="hotel_id" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ old('hotel_id', $room->hotel_id) == $hotel->id ? 'selected' : '' }}>
                                {{ $hotel->name }} ({{ $hotel->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название квартиры *</label>
                    <input type="text" name="name" value="{{ old('name', $room->name) }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание *</label>
                    <textarea name="description" rows="6" required
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">{{ old('description', $room->description) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Тип квартиры *</label>
                    <select name="type" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                        <option value="standard" {{ old('type', $room->type) == 'standard' ? 'selected' : '' }}>Стандарт</option>
                        <option value="superior" {{ old('type', $room->type) == 'superior' ? 'selected' : '' }}>Улучшенная</option>
                        <option value="deluxe" {{ old('type', $room->type) == 'deluxe' ? 'selected' : '' }}>Делюкс</option>
                        <option value="suite" {{ old('type', $room->type) == 'suite' ? 'selected' : '' }}>Люкс</option>
                        <option value="family" {{ old('type', $room->type) == 'family' ? 'selected' : '' }}>Семейная</option>
                        <option value="studio" {{ old('type', $room->type) == 'studio' ? 'selected' : '' }}>Студия</option>
                        <option value="apartment" {{ old('type', $room->type) == 'apartment' ? 'selected' : '' }}>Апартаменты</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Вместимость взрослых *</label>
                        <input type="number" name="capacity_adults" value="{{ old('capacity_adults', $room->capacity_adults) }}" min="1" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Вместимость детей</label>
                        <input type="number" name="capacity_children" value="{{ old('capacity_children', $room->capacity_children) }}" min="0"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Цена за ночь (₽) *</label>
                        <input type="number" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" min="0" step="0.01" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Количество *</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $room->quantity) }}" min="1" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl mb-6">
                    <p class="text-sm">После сохранения изменений квартира будет отправлена на повторную модерацию.</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 py-4 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white font-medium rounded-xl transition-all">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('user.rooms.index') }}" class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-xl transition-colors">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(previewId.replace('Preview', 'Placeholder'));
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
