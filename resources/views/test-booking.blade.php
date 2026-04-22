<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест бронирования</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #3B82F6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #2563EB;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error {
            background: #ffebee;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #c62828;
        }
    </style>
</head>
<body>
    <h1>Тестовая форма бронирования</h1>
    
    <div class="info">
        <p><strong>Цель:</strong> Проверить, доходит ли POST-запрос до контроллера</p>
        <p><strong>Пользователь:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Action URL:</strong> {{ route('bookings.store') }}</p>
    </div>

    @if ($errors->any())
        <div class="error">
            <p><strong>Ошибки валидации:</strong></p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('bookings.store') }}" method="POST" id="testForm">
        @csrf
        
        <div class="form-group">
            <label>Room ID:</label>
            <input type="number" name="room_id" value="1" required>
        </div>

        <div class="form-group">
            <label>Дата заезда:</label>
            <input type="date" name="check_in" value="{{ now()->addDay()->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label>Дата выезда:</label>
            <input type="date" name="check_out" value="{{ now()->addDays(3)->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label>Взрослых:</label>
            <input type="number" name="adults" value="2" min="1" required>
        </div>

        <div class="form-group">
            <label>Детей:</label>
            <input type="number" name="children" value="0" min="0">
        </div>

        <div class="form-group">
            <label>Особые пожелания:</label>
            <textarea name="special_requests" rows="3">Тестовое бронирование</textarea>
        </div>

        <button type="submit">Отправить тестовое бронирование</button>
    </form>

    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            console.log('=== TEST FORM SUBMIT ===');
            console.log('Action:', this.action);
            console.log('Method:', this.method);
            
            const formData = new FormData(this);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log('  ' + key + ':', value);
            }
            
            alert('Форма отправляется! Проверьте консоль браузера и логи Laravel.');
        });
    </script>
</body>
</html>
