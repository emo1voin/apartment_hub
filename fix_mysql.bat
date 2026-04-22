@echo off
echo ========================================
echo   Исправление MySQL в XAMPP
echo ========================================
echo.

echo Этот скрипт попытается исправить проблему с MySQL
echo.
echo ВАЖНО: Закройте все программы, использующие MySQL!
echo.
pause

echo.
echo [1/5] Остановка MySQL...
cd C:\xampp
mysql_stop.bat
timeout /t 3 /nobreak >nul

echo.
echo [2/5] Запуск MySQL в безопасном режиме...
start "MySQL Safe Mode" cmd /k "cd C:\xampp\mysql\bin && mysqld --skip-grant-tables --skip-networking"
timeout /t 5 /nobreak >nul

echo.
echo [3/5] Сброс пароля root...
cd C:\xampp\mysql\bin
mysql -u root -e "FLUSH PRIVILEGES; ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''; CREATE DATABASE IF NOT EXISTS booking_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; FLUSH PRIVILEGES;"

if %errorlevel% equ 0 (
    echo [OK] Пароль сброшен успешно!
) else (
    echo [ОШИБКА] Не удалось сбросить пароль
)

echo.
echo [4/5] Остановка безопасного режима...
taskkill /F /FI "WINDOWTITLE eq MySQL Safe Mode*" >nul 2>&1
timeout /t 2 /nobreak >nul

echo.
echo [5/5] Запуск MySQL в нормальном режиме...
cd C:\xampp
mysql_start.bat
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo   Готово!
echo ========================================
echo.
echo Проверьте phpMyAdmin: http://localhost/phpmyadmin
echo.
echo Если всё работает, запустите:
echo   php artisan migrate
echo   php artisan db:seed --class=BookingSystemSeeder
echo.
pause
