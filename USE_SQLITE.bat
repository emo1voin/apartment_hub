@echo off
echo ========================================
echo   Переключение на SQLite
echo ========================================
echo.

echo Этот скрипт настроит проект для работы с SQLite
echo вместо MySQL. Это проще и не требует настройки!
echo.
pause

echo.
echo [1/4] Создание файла базы данных SQLite...
if not exist database\database.sqlite (
    type nul > database\database.sqlite
    echo [OK] Файл database.sqlite создан
) else (
    echo [OK] Файл database.sqlite уже существует
)

echo.
echo [2/4] Обновление .env файла...
powershell -Command "(Get-Content .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=sqlite' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'SESSION_DRIVER=.*', 'SESSION_DRIVER=file' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'CACHE_STORE=.*', 'CACHE_STORE=file' | Set-Content .env"
echo [OK] .env обновлен

echo.
echo [3/4] Очистка кэша...
call php artisan config:clear
call php artisan cache:clear
echo [OK] Кэш очищен

echo.
echo [4/4] Запуск миграций...
call php artisan migrate
if %errorlevel% equ 0 (
    echo [OK] Миграции выполнены успешно!
    
    echo.
    echo Заполнение тестовыми данными...
    call php artisan db:seed --class=BookingSystemSeeder
    
    if %errorlevel% equ 0 (
        echo [OK] Тестовые данные добавлены!
    )
) else (
    echo [ОШИБКА] Не удалось выполнить миграции
)

echo.
echo ========================================
echo   Готово!
echo ========================================
echo.
echo Теперь запустите:
echo   npm run dev (в одном терминале)
echo   php artisan serve (в другом терминале)
echo.
echo Откройте: http://localhost:8000
echo.
echo Логин: admin@booking.com
echo Пароль: password
echo.
pause
