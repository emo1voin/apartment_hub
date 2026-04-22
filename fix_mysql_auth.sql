-- Скрипт для исправления проблемы аутентификации MySQL/MariaDB

-- 1. Создаем базу данных
CREATE DATABASE IF NOT EXISTS booking_system 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 2. Исправляем метод аутентификации для пользователя root
-- Для MySQL 8.0+
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';
FLUSH PRIVILEGES;

-- Если используется MariaDB, используйте эту команду вместо предыдущей:
-- ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('');
-- FLUSH PRIVILEGES;

-- 3. Проверяем созданную базу данных
SHOW DATABASES LIKE 'booking_system';

-- 4. Выбираем базу данных
USE booking_system;

-- Готово! Теперь можно запускать миграции Laravel
