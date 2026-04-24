<!-- Раздел отзывов -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mt-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Отзывы гостей</h2>
        @if($apiAuth->check())
            <button onclick="document.getElementById('reviewForm').scrollIntoView({behavior: 'smooth'})" 
                    class="px-4 py-2 bg-[#3B82F6] text-white rounded-lg hover:bg-[#2563EB] transition-colors">
                Написать отзыв
            </button>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2 bg-[#3B82F6] text-white rounded-lg hover:bg-[#2563EB] transition-colors">
                Войти чтобы написать отзыв
            </a>
        @endif
    </div>

    @if($hotel->reviews->count() > 0)
        <!-- Общая статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-6 bg-gray-50 rounded-xl">
            <div class="text-center">
                <div class="text-5xl font-bold text-[#3B82F6] mb-2">{{ number_format($hotel->rating, 1) }}</div>
                <div class="flex justify-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= round($hotel->rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <div class="text-gray-600">На основе {{ $hotel->review_count }} отзывов</div>
            </div>

            <div class="space-y-2">
                @if($hotel->rating_cleanliness)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 w-24">Чистота</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-[#3B82F6] h-2 rounded-full" style="width: {{ ($hotel->rating_cleanliness / 5) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 w-8">{{ number_format($hotel->rating_cleanliness, 1) }}</span>
                    </div>
                @endif
                @if($hotel->rating_comfort)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 w-24">Комфорт</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-[#3B82F6] h-2 rounded-full" style="width: {{ ($hotel->rating_comfort / 5) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 w-8">{{ number_format($hotel->rating_comfort, 1) }}</span>
                    </div>
                @endif
                @if($hotel->rating_location)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 w-24">Расположение</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-[#3B82F6] h-2 rounded-full" style="width: {{ ($hotel->rating_location / 5) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 w-8">{{ number_format($hotel->rating_location, 1) }}</span>
                    </div>
                @endif
                @if($hotel->rating_service)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 w-24">Сервис</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-[#3B82F6] h-2 rounded-full" style="width: {{ ($hotel->rating_service / 5) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 w-8">{{ number_format($hotel->rating_service, 1) }}</span>
                    </div>
                @endif
                @if($hotel->rating_value)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 w-24">Цена/качество</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-[#3B82F6] h-2 rounded-full" style="width: {{ ($hotel->rating_value / 5) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 w-8">{{ number_format($hotel->rating_value, 1) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Список отзывов -->
        <div class="space-y-6">
            @foreach($hotel->reviews()->where('is_approved', true)->latest()->take(10)->get() as $review)
                <div class="border-b border-gray-100 pb-6 last:border-0">
                    <div class="flex items-start gap-4">
                        <!-- Аватар -->
                        <div class="w-12 h-12 bg-gradient-to-br from-[#3B82F6] to-blue-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>

                        <div class="flex-1">
                            <!-- Заголовок отзыва -->
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $review->user->name }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="flex gap-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        @if($review->is_verified)
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">Подтверждено</span>
                                        @endif
                                    </div>
                                </div>
                                @if($apiAuth->check() && ($review->user_id === $apiAuth->id() || $apiAuth->isAdmin()))
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Удалить отзыв?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Удалить</button>
                                        </form>
                                    @endif
                            </div>

                            <!-- Заголовок отзыва -->
                            @if($review->title)
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $review->title }}</h4>
                            @endif

                            <!-- Текст отзыва -->
                            <p class="text-gray-700 mb-3">{{ $review->comment }}</p>

                            <!-- Плюсы и минусы -->
                            @if($review->pros || $review->cons)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                    @if($review->pros)
                                        <div class="bg-green-50 p-3 rounded-lg">
                                            <div class="text-sm font-semibold text-green-800 mb-1">✓ Плюсы:</div>
                                            <div class="text-sm text-green-700">{{ $review->pros }}</div>
                                        </div>
                                    @endif
                                    @if($review->cons)
                                        <div class="bg-red-50 p-3 rounded-lg">
                                            <div class="text-sm font-semibold text-red-800 mb-1">✗ Минусы:</div>
                                            <div class="text-sm text-red-700">{{ $review->cons }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Рекомендация -->
                            @if($review->is_recommended)
                                <div class="mt-3 inline-flex items-center gap-2 text-sm text-green-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                    </svg>
                                    Рекомендует этот дом
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <p class="text-gray-500 mb-4">Пока нет отзывов об этом доме</p>
            <p class="text-sm text-gray-400">Станьте первым, кто оставит отзыв!</p>
        </div>
    @endif

    <!-- Форма добавления отзыва -->
    @if($apiAuth->check())
        <div id="reviewForm" class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Написать отзыв</h3>
            <form action="{{ route('reviews.store', $hotel) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Общая оценка -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Общая оценка *</label>
                    <div class="flex gap-2" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" required class="sr-only" onchange="updateStars({{ $i }})">
                                <svg class="w-8 h-8 text-gray-300 hover:text-yellow-300 transition-colors star-icon" data-star="{{ $i }}" fill="currentColor" viewBox="0 0 20 20" onclick="updateStars({{ $i }})">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    <script>
                    function updateStars(rating) {
                        document.querySelectorAll('.star-icon').forEach(function(s) {
                            if (parseInt(s.dataset.star) <= rating) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    }
                    </script>
                </div>

                <!-- Детальные оценки -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Чистота</label>
                        <select name="rating_cleanliness" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg">
                            <option value="">-</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Комфорт</label>
                        <select name="rating_comfort" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg">
                            <option value="">-</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Расположение</label>
                        <select name="rating_location" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg">
                            <option value="">-</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Сервис</label>
                        <select name="rating_service" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg">
                            <option value="">-</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Цена/качество</label>
                        <select name="rating_value" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg">
                            <option value="">-</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Заголовок -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Заголовок</label>
                    <input type="text" name="title" maxlength="255"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                </div>

                <!-- Комментарий -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ваш отзыв *</label>
                    <textarea name="comment" rows="4" required minlength="10"
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all resize-none"
                              placeholder="Расскажите о вашем опыте..."></textarea>
                </div>

                <!-- Плюсы и минусы -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Плюсы</label>
                        <textarea name="pros" rows="2"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Минусы</label>
                        <textarea name="cons" rows="2"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                </div>

                <!-- Тип поездки -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Тип поездки</label>
                    <select name="travel_type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent transition-all">
                        <option value="">Не указано</option>
                        <option value="alone">Соло</option>
                        <option value="couple">Пара</option>
                        <option value="family">Семья</option>
                        <option value="friends">С друзьями</option>
                        <option value="business">Бизнес</option>
                    </select>
                </div>

                <!-- Рекомендация -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_recommended" value="1" class="w-4 h-4 text-[#3B82F6] border-gray-300 rounded focus:ring-[#3B82F6]">
                        <span class="ml-2 text-sm text-gray-700">Я рекомендую этот дом</span>
                    </label>
                </div>

                <!-- Кнопка отправки -->
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#3B82F6] to-blue-600 hover:from-[#2563EB] hover:to-blue-700 text-white font-medium rounded-xl transition-all hover:scale-[1.02] shadow-lg">
                    Опубликовать отзыв
                </button>
            </form>
        </div>
    @endif
</div>
