<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Залы — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Залы фотостудии</h1>

    <p><a href="{{ route('home') }}">← На главную</a></p>

    <ul>
        @foreach($halls as $hall)
            <li style="margin-bottom: 12px;">
                <b>{{ $hall->name }}</b>
                ({{ $hall->studio->name }}) —
                {{ $hall->base_price_per_hour }} ₽/час
                <br>
                <a href="{{ route('halls.show', $hall) }}">Открыть</a>
                @auth
                    | <a href="{{ route('bookings.create', $hall) }}">Забронировать</a>
                @endauth
            </li>
        @endforeach
    </ul>
</body>
</html>
