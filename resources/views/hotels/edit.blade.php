@extends('layouts.app')

@section('title', 'Редактировать дом')

@section('content')
<div class="container mx-auto px-4 lg:px-8 max-w-4xl py-12">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Редактировать дом</h1>
            <p class="text-gray-500 mt-2">Обновите информацию о доме</p>
        </div>

        <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Название -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Название дома <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $hotel->name) }}"
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
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent">{{ old('description', $hotel->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Адрес -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Адрес <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="address" 
                       name="address" 
                       value="{{ old('address', $hotel->address) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                       required>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Город и Страна -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Город <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="city" 
                           name="city" 
                           value="{{ old('city', $hotel->city) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                           required>
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                        Страна <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="country" 
                           name="country" 
                           value="{{ old('country', $hotel->country) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                           required>
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Телефон и Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Телефон
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $hotel->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $hotel->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Рейтинг -->
            <div>
                <label for="stars" class="block text-sm font-medium text-gray-700 mb-2">
                    Рейтинг (звезды) <span class="text-red-500">*</span>
                </label>
                <select id="stars" 
                        name="stars" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent"
                        required>
                    <option value="">Выберите рейтинг</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('stars', $hotel->stars) == $i ? 'selected' : '' }}>
                            {{ $i }} {{ $i == 1 ? 'звезда' : ($i < 5 ? 'звезды' : 'звезд') }}
                        </option>
                    @endfor
                </select>
                @error('stars')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Кнопки -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('hotels.show', $hotel) }}" 
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

