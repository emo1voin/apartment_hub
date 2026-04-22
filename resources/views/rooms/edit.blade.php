@extends('layouts.app')

@section('title', 'Редактировать квартиру')

@section('content')
<div class="container mx-auto px-4 lg:px-8 max-w-4xl py-12">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Редактировать квартиру</h1>
            <p class="text-gray-500 mt-2">Обновите информацию о квартире в доме "{{ $room->hotel->name }}"</p>
        </div>

        <form action="{{ route('admin.rooms.update', $room) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Название -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Название квартиры <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $room->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Описание -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Описание
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent">{{ old('description', $room->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Тип квартиры -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Тип квартиры <span class="text-red-500">*</span>
                </label>
                <select id="type" 
                        name="type" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                        required>
                    <option value="">Выберите тип</option>
                    <option value="standard" {{ old('type', $room->type) == 'standard' ? 'selected' : '' }}>Стандарт</option>
                    <option value="superior" {{ old('type', $room->type) == 'superior' ? 'selected' : '' }}>Улучшенная</option>
                    <option value="deluxe" {{ old('type', $room->type) == 'deluxe' ? 'selected' : '' }}>Делюкс</option>
                    <option value="suite" {{ old('type', $room->type) == 'suite' ? 'selected' : '' }}>Люкс</option>
                    <option value="family" {{ old('type', $room->type) == 'family' ? 'selected' : '' }}>Семейная</option>
                    <option value="studio" {{ old('type', $room->type) == 'studio' ? 'selected' : '' }}>Студия</option>
                    <option value="apartment" {{ old('type', $room->type) == 'apartment' ? 'selected' : '' }}>Апартаменты</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Вместимость -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="capacity_adults" class="block text-sm font-medium text-gray-700 mb-2">
                        Взрослых <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="capacity_adults" 
                           name="capacity_adults" 
                           value="{{ old('capacity_adults', $room->capacity_adults) }}"
                           min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                           required>
                    @error('capacity_adults')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacity_children" class="block text-sm font-medium text-gray-700 mb-2">
                        Детей
                    </label>
                    <input type="number" 
                           id="capacity_children" 
                           name="capacity_children" 
                           value="{{ old('capacity_children', $room->capacity_children) }}"
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent">
                    @error('capacity_children')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Цена и количество -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-2">
                        Цена за ночь (₽) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="price_per_night" 
                           name="price_per_night" 
                           value="{{ old('price_per_night', $room->price_per_night) }}"
                           min="0"
                           step="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                           required>
                    @error('price_per_night')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        Количество <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="quantity" 
                           name="quantity" 
                           value="{{ old('quantity', $room->quantity) }}"
                           min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                           required>
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Кнопки -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('rooms.show', $room) }}" 
                   class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Отмена
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-[#3B82F6] text-white hover:bg-[#2563EB] rounded-lg transition-colors">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

