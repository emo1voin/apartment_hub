<?php

echo "===========================================\n";
echo "  Создание базы данных booking_system\n";
echo "===========================================\n\n";

try {
    // Подключаемся к MySQL без выбора базы данных
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "[1/3] Подключение к MySQL... ✓\n";
    
    // Создаем базу данных
    $pdo->exec('CREATE DATABASE IF NOT EXISTS booking_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "[2/3] База данных booking_system создана... ✓\n";
    
    // Исправляем метод аутентификации
    try {
        $pdo->exec("ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''");
        $pdo->exec("FLUSH PRIVILEGES");
        echo "[3/3] Метод аутентификации исправлен... ✓\n";
    } catch (PDOException $e) {
        echo "[3/3] Метод аутентификации (пропущено)... ⚠\n";
    }
    
    echo "\n===========================================\n";
    echo "  ✓ УСПЕШНО!\n";
    echo "===========================================\n\n";
    echo "Теперь можно запустить:\n";
    echo "  php artisan migrate\n";
    echo "  php artisan db:seed --class=BookingSystemSeeder\n\n";
    
} catch (PDOException $e) {
    echo "\n===========================================\n";
    echo "  ✗ ОШИБКА!\n";
    echo "===========================================\n\n";
    echo "Сообщение: " . $e->getMessage() . "\n\n";
    
    echo "Возможные причины:\n";
    echo "1. MySQL не запущен в XAMPP\n";
    echo "2. Неверный пароль (измените в скрипте)\n";
    echo "3. Порт 3306 занят\n\n";
    
    echo "Решение:\n";
    echo "1. Запустите MySQL в XAMPP Control Panel\n";
    echo "2. Проверьте пароль root в phpMyAdmin\n";
    echo "3. Откройте phpMyAdmin и создайте БД вручную\n\n";
}
