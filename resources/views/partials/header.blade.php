<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-100">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Логотип -->
            <a href="{{ route('hotels.index') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                <div class="w-10 h-10 bg-gradient-to-br from-[#3B82F6] to-[#60A5FA] rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900">
                    ApartmentHub
                </span>
            </a>

            <!-- Десктопная навигация -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('hotels.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Квартиры</a>
                @if($apiAuth->check())
                    <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Мои бронирования</a>
                    @if($apiAuth->isAdmin())
                        <a href="{{ route('admin.moderation.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Модерация</a>
                    @endif
                @endif
            </nav>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                @if($apiAuth->check())
                    <div class="relative">
                        <button onclick="toggleUserMenu(event)" 
                                type="button"
                                id="user-menu-button"
                                class="flex items-center space-x-2 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-gradient-to-br from-[#3B82F6] to-[#60A5FA] flex items-center justify-center text-white font-medium text-sm">
                                {{ strtoupper(substr($apiAuth->name(), 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ $apiAuth->name() }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" id="user-menu-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="user-menu-dropdown"
                             style="display: none;"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-[200]">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ $apiAuth->name() }}</p>
                                <p class="text-xs text-gray-500">{{ $apiAuth->email() }}</p>
                                @if($apiAuth->isAdmin())
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-[#3B82F6] text-white rounded-full">Администратор</span>
                                @endif
                            </div>

                            <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Мои бронирования</a>
                            <a href="{{ route('user.hotels.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Мои дома</a>
                            <a href="{{ route('user.rooms.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Мои квартиры</a>
                            
                            @if($apiAuth->isAdmin())
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('admin.moderation.index') }}" class="block px-4 py-2 text-sm text-[#3B82F6] hover:bg-blue-50 font-medium">Модерация</a>
                            @endif
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Выйти</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Вход</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm bg-[#3B82F6] text-white hover:bg-[#2563EB] rounded-lg transition-colors">Регистрация</a>
                @endif

                <!-- Мобильное меню -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu(event)"
                            type="button"
                            id="mobile-menu-button"
                            class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div id="mobile-menu-dropdown"
                         style="display: none;"
                         class="absolute right-0 left-0 top-16 bg-white border-t border-gray-100 py-4 px-4 shadow-lg">
                        <nav class="flex flex-col space-y-3">
                            <a href="{{ route('hotels.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Квартиры</a>
                            @if($apiAuth->check())
                                <a href="{{ route('bookings.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Мои бронирования</a>
                                @if($apiAuth->isAdmin())
                                    <a href="{{ route('admin.moderation.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Модерация</a>
                                @endif
                                <div class="border-t border-gray-100 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg">Выйти</button>
                                </form>
                            @else
                                <div class="border-t border-gray-100 my-2"></div>
                                <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Вход</a>
                                <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Регистрация</a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleUserMenu(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('user-menu-dropdown');
        const arrow = document.getElementById('user-menu-arrow');
        
        if (dropdown.style.display === 'none') {
            dropdown.style.display = 'block';
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.style.display = 'none';
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    
    function toggleMobileMenu(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('mobile-menu-dropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const userButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-menu-dropdown');
        const mobileButton = document.getElementById('mobile-menu-button');
        const mobileDropdown = document.getElementById('mobile-menu-dropdown');
        
        // Close user menu if clicking outside
        if (userButton && userDropdown && 
            !userButton.contains(event.target) && 
            !userDropdown.contains(event.target)) {
            userDropdown.style.display = 'none';
            const arrow = document.getElementById('user-menu-arrow');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
        
        // Close mobile menu if clicking outside
        if (mobileButton && mobileDropdown && 
            !mobileButton.contains(event.target) && 
            !mobileDropdown.contains(event.target)) {
            mobileDropdown.style.display = 'none';
        }
    });
</script>
