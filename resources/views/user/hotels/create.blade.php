@extends('layouts.app')

@section('title', 'Добавить дом')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 lg:px-8 max-w-3xl">
        <h1 class="text-3xl font-light text-gray-900 mb-8">Добавить дом</h1>

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
            <form action="{{ route('user.hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Фото дома</label>
                    <div class="relative">
                        <input type="file" name="main_image" accept="image/jpeg,image/png,image/jpg,image/webp" id="hotelImage"
                               class="hidden" onchange="previewImage(this, 'hotelPreview')">
                        <label for="hotelImage" class="flex flex-col items-center justify-center w-full h-48 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-[#3B82F6] hover:bg-blue-50 transition-all" id="hotelPreviewLabel">
                            <img id="hotelPreview" class="hidden w-full h-full object-cover rounded-xl">
                            <div id="hotelPlaceholder" class="text-center">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название дома *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Краткое описание</label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="500"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Полное описание *</label>
                    <textarea name="description" rows="6" required
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">{{ old('description') }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Адрес *</label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Город *</label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Страна *</label>
                        <input type="text" name="country" value="{{ old('country') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Телефон</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Количество звёзд *</label>
                    <select name="stars" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6]">
                        <option value="">-- Выберите --</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('stars') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'звезда' : ($i < 5 ? 'звезды' : 'звёзд') }} {{ str_repeat('★', $i) }}</option>
                        @endfor
                    </select>
                </div>

                <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl mb-6">
                    <p class="text-sm">После отправки дом будет проверен администратором. Вы получите уведомление о результатах модерации.</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 py-4 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white font-medium rounded-xl transition-all">
                        Отправить на модерацию
                    </button>
                    <a href="{{ route('user.hotels.index') }}" class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-xl transition-colors">
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
