<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{{ $hall->name }} — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>{{ $hall->name }}</h1>
    <p><a href="{{ route('halls.index') }}">← Назад к залам</a></p>

    <p><b>Студия:</b> {{ $hall->studio->name }}</p>
    <p><b>Цена:</b> {{ $hall->base_price_per_hour }} ₽/час (база, далее множитель)</p>
    <p><b>Описание:</b> {{ $hall->description ?? '—' }}</p>

    @auth
        <p>
            <a href="{{ route('bookings.create', $hall) }}">Забронировать этот зал</a>
        </p>
    @else
        <p>
            Чтобы бронировать:
            @if (Route::has('login'))
                <a href="{{ route('login') }}">войдите</a>.
            @else
                <a href="{{ route('home') }}">перейдите на главную</a>.
            @endif
        </p>
    @endauth
</body>
</html>
