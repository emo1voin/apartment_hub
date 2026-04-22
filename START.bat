@echo off
echo ========================================
echo   Система бронирования отелей
echo ========================================
echo.

echo [1/7] Установка зависимостей PHP...
call composer install
if %errorlevel% neq 0 (
    echo Ошибка при установке зависимостей PHP
    pause
    exit /b %errorlevel%
)

echo.
echo [2/7] Установка зависимостей JavaScript...
call npm install
if %errorlevel% neq 0 (
    echo Ошибка при установке зависимостей JavaScript
    pause
    exit /b %errorlevel%
)

echo.
echo [3/7] Копирование .env файла...
if not exist .env (
    copy .env.example .env
    echo .env файл создан. Пожалуйста, настройте подключение к базе данных!
    echo Откройте .env и укажите:
    echo   DB_DATABASE=booking_system
    echo   DB_USERNAME=root
    echo   DB_PASSWORD=ваш_пароль
    echo.
    echo После настройки нажмите любую клавишу для продолжения...
    pause
)

echo.
echo [4/7] Генерация ключа приложения...
call php artisan key:generate

echo.
echo [5/7] Запуск миграций...
call php artisan migrate
if %errorlevel% neq 0 (
    echo.
    echo ВНИМАНИЕ: Убедитесь, что:
    echo 1. MySQL запущен
    echo 2. База данных 'booking_system' создана
    echo 3. Настройки в .env файле корректны
    echo.
    pause
    exit /b %errorlevel%
)

echo.
echo [6/7] Заполнение тестовыми данными...
call php artisan db:seed --class=BookingSystemSeeder

echo.
echo [7/7] Компиляция ассетов...
start /B npm run dev

echo.
echo ========================================
echo   Установка завершена!
echo ========================================
echo.
echo Тестовые аккаунты:
echo   Админ: admin@booking.com / password
echo   Пользователь: user@booking.com / password
echo.
echo Запуск сервера...
echo Откройте http://localhost:8000 в браузере
echo.
call php artisan serve
