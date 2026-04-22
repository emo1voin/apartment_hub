@extends('layouts.app')

@section('title', 'Регистрация - ApartmentHub')

@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Логотип -->
            <div class="text-center mb-8">
                <a href="{{ route('hotels.index') }}" class="inline-flex items-center text-2xl font-bold tracking-tight">
                    <span class="text-gray-900">ApartmentHub</span>
                </a>
                <h2 class="mt-6 text-3xl font-light text-gray-900">
                    Создайте аккаунт
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Присоединяйтесь к нам сегодня
                </p>
            </div>

            <!-- Форма регистрации -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Имя -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Полное имя
                        </label>
                        <input id="name"
                               name="name"
                               type="text"
                               autocomplete="name"
                               required
                               autofocus
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all @error('name') border-red-300 focus:ring-red-200 @enderror"
                               placeholder="Иван Иванов">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email адрес
                        </label>
                        <input id="email"
                               name="email"
                               type="email"
                               autocomplete="email"
                               required
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all @error('email') border-red-300 focus:ring-red-200 @enderror"
                               placeholder="you@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Пароль -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Пароль
                        </label>
                        <input id="password"
                               name="password"
                               type="password"
                               autocomplete="new-password"
                               required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all @error('password') border-red-300 focus:ring-red-200 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Подтверждение пароля -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Подтвердите пароль
                        </label>
                        <input id="password_confirmation"
                               name="password_confirmation"
                               type="password"
                               autocomplete="new-password"
                               required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent transition-all"
                               placeholder="••••••••">
                    </div>

                    <!-- Кнопка регистрации -->
                    <button type="submit"
                            class="w-full py-3.5 px-4 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-all hover:scale-[1.02] shadow-lg">
                        Зарегистрироваться
                    </button>
                </form>

                <!-- Разделитель -->
                <div class="mt-6 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">или</span>
                    </div>
                </div>

                <!-- Вход -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Уже есть аккаунт?
                        <a href="{{ route('login') }}" class="font-medium text-gray-900 hover:text-gray-700 transition-colors">
                            Войти
                        </a>
                    </p>
                </div>
            </div>

            <!-- Дополнительная информация -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">
                    Регистрируясь, вы соглашаетесь с нашими условиями использования
                </p>
            </div>
        </div>
    </div>
@endsection
