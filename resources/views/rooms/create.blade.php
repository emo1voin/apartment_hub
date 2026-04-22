@extends('layouts.app')

@section('title', 'Добавить квартиру')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="mb-6">
                <a href="{{ route('hotels.show', $hotel) }}" class="text-sm text-gray-500 hover:text-gray-700">< Назад к дому</a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h1 class="text-3xl font-light text-gray-900 mb-2">Добавить квартиру</h1>
                <p class="text-gray-500 mb-6">Дом: {{ $hotel->name }}</p>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.rooms.store', $hotel) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Главное изображение -->
                    <div>
                        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Главное изображение
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#3B82F6] transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="main_image" class="relative cursor-pointer bg-white rounded-md font-medium text-[#3B82F6] hover:text-[#2563EB]">
                                        <span>Загрузить файл</span>
                                        <input id="main_image" name="main_image" type="file" accept="image/*" class="sr-only" onchange="previewMainImage(event)">
                                    </label>
                                    <p class="pl-1">или перетащите сюда</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF до 10MB</p>
                            </div>
                        </div>
                        <div id="main-image-preview" class="mt-4 hidden">
                            <img src="" alt="Preview" class="max-w-xs rounded-lg shadow-md mx-auto">
                        </div>
                    </div>

                    <!-- Название -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Название квартиры *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                    </div>

                    <!-- Описание -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all resize-none">{{ old('description') }}</textarea>
                    </div>

                    <!-- Вместимость -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="capacity_adults" class="block text-sm font-medium text-gray-700 mb-2">Взрослых *</label>
                            <input type="number" name="capacity_adults" id="capacity_adults" required min="1" value="{{ old('capacity_adults', 2) }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="capacity_children" class="block text-sm font-medium text-gray-700 mb-2">Детей</label>
                            <input type="number" name="capacity_children" id="capacity_children" min="0" value="{{ old('capacity_children', 0) }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Кровати -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bed_type" class="block text-sm font-medium text-gray-700 mb-2">Тип кровати *</label>
                            <select name="bed_type" id="bed_type" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                                <option value="single" {{ old('bed_type') == 'single' ? 'selected' : '' }}>Односпальная</option>
                                <option value="double" {{ old('bed_type') == 'double' ? 'selected' : '' }}>Двуспальная</option>
                                <option value="queen" {{ old('bed_type') == 'queen' ? 'selected' : '' }}>Queen</option>
                                <option value="king" {{ old('bed_type') == 'king' ? 'selected' : '' }}>King</option>
                            </select>
                        </div>
                        <div>
                            <label for="bed_count" class="block text-sm font-medium text-gray-700 mb-2">Количество кроватей *</label>
                            <input type="number" name="bed_count" id="bed_count" required min="1" value="{{ old('bed_count', 1) }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Площадь -->
                    <div>
                        <label for="size_sqm" class="block text-sm font-medium text-gray-700 mb-2">Площадь (м²)</label>
                        <input type="number" name="size_sqm" id="size_sqm" min="1" value="{{ old('size_sqm') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                    </div>

                    <!-- Цена -->
                    <div>
                        <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-2">Цена за ночь (₽) *</label>
                        <input type="number" name="price_per_night" id="price_per_night" required min="0" step="0.01" value="{{ old('price_per_night') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                    </div>

                    <!-- Доступность -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#3B82F6] border-gray-300 rounded focus:ring-[#3B82F6]">
                            <span class="ml-2 text-sm text-gray-700">Квартира доступна для бронирования</span>
                        </label>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                                class="flex-1 py-3 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white font-medium rounded-xl transition-all hover:scale-[1.02] shadow-lg">
                            Создать квартиру
                        </button>
                        <a href="{{ route('hotels.show', $hotel) }}"
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium rounded-xl transition-colors">
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewMainImage(event) {
            const preview = document.getElementById('main-image-preview');
            const img = preview.querySelector('img');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
