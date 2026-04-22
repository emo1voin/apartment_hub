@extends('layouts.app')

@section('title', 'Добавить дом')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="mb-6">
                <a href="{{ route('hotels.index') }}" class="text-sm text-gray-500 hover:text-gray-700">< Назад к домам</a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h1 class="text-3xl font-light text-gray-900 mb-6">Добавить новый дом</h1>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Название дома *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                    </div>

                    <!-- Описание -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all resize-none">{{ old('description') }}</textarea>
                    </div>

                    <!-- Адрес -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Адрес *</label>
                            <input type="text" name="address" id="address" required value="{{ old('address') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Город *</label>
                            <input type="text" name="city" id="city" required value="{{ old('city') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Страна *</label>
                            <input type="text" name="country" id="country" required value="{{ old('country') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Почтовый индекс</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Контакты -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Веб-сайт</label>
                        <input type="url" name="website" id="website" value="{{ old('website') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                    </div>

                    <!-- Время заезда/выезда -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="check_in_time" class="block text-sm font-medium text-gray-700 mb-2">Время заезда *</label>
                            <input type="time" name="check_in_time" id="check_in_time" required value="{{ old('check_in_time', '14:00') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="check_out_time" class="block text-sm font-medium text-gray-700 mb-2">Время выезда *</label>
                            <input type="time" name="check_out_time" id="check_out_time" required value="{{ old('check_out_time', '12:00') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Координаты -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Широта (от -90 до 90)</label>
                            <input type="number" step="0.000001" name="latitude" id="latitude" value="{{ old('latitude') }}" min="-90" max="90"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Долгота (от -180 до 180)</label>
                            <input type="number" step="0.000001" name="longitude" id="longitude" value="{{ old('longitude') }}" min="-180" max="180"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Звездность -->
                    <div>
                        <label for="stars" class="block text-sm font-medium text-gray-700 mb-2">Звездность *</label>
                        <select name="stars" id="stars" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('stars') == $i ? 'selected' : '' }}>{{ $i }} звезд</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                                class="flex-1 py-3 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white font-medium rounded-xl transition-all hover:scale-[1.02] shadow-lg">
                            Создать дом
                        </button>
                        <a href="{{ route('hotels.index') }}"
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
