<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Мои бронирования</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Мои бронирования</h1>

    <p>
        <a href="{{ route('home') }}">← На главную</a> |
        <a href="{{ route('halls.index') }}">Залы</a>
    </p>

    @if($bookings->count() === 0)
        <p>Бронирований пока нет.</p>
    @else
        <ul>
            @foreach($bookings as $b)
                <li style="margin-bottom: 14px;">
                    <b>#{{ $b->id }}</b> — {{ $b->status }} —
                    {{ $b->hall->name }} —
                    {{ $b->start_at->format('Y-m-d H:i') }} → {{ $b->end_at->format('Y-m-d H:i') }}
                    — {{ $b->price_total }} ₽
                    <br>
                    <a href="{{ route('bookings.show', $b) }}">Открыть</a>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
