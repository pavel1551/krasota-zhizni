<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Панель управления — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Панель управления</h1>

    <p>Вы вошли в систему как: <b>{{ auth()->user()->name }}</b></p>

    <ul>
        <li><a href="{{ route('halls.index') }}">Залы фотостудии</a></li>
        <li><a href="{{ route('bookings.history') }}">Мои бронирования</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Выйти</button>
            </form>
        </li>
    </ul>

    <hr>
    <p style="color:#777;">
        Это демонстрационная “панель управления” для проекта бронирования фотостудии «Красота-Жизни».
    </p>
</body>
</html>
