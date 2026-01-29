<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $brand ?? 'Красота-Жизни' }}</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Фотостудия «{{ $brand ?? 'Красота-Жизни' }}»</h1>

    <p>
        Демонстрационный MVP системы бронирования: динамическая цена, реквизит с ограничением количества,
        возврат по правилам и заглушка оплаты.
    </p>

    <hr>

    @if (Route::has('login'))
        @auth
            <p>
                Вы вошли в систему как: <b>{{ auth()->user()->name }}</b>
            </p>

            <ul>
                <li><a href="{{ route('dashboard') }}">Панель управления</a></li>
                <li><a href="{{ route('halls.index') }}">Посмотреть залы</a></li>
                <li><a href="{{ route('bookings.history') }}">Мои бронирования</a></li>
            </ul>

            <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
                @csrf
                <button type="submit">Выйти</button>
            </form>
        @else
            <ul>
                <li><a href="{{ route('halls.index') }}">Посмотреть залы</a></li>
                <li>
                    <a href="{{ route('login') }}">Войти</a>
                    /
                    <a href="{{ route('register') }}">Регистрация</a>
                </li>
            </ul>
        @endauth
    @else
        <p style="color:#b00;">
            Роуты авторизации не подключены (login/register не найдены).
            Проверь, что в routes/web.php есть строка: require __DIR__.'/auth.php';
        </p>

        <ul>
            <li><a href="{{ route('halls.index') }}">Посмотреть залы</a></li>
        </ul>
    @endif

</body>
</html>
