<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Бронь #{{ $booking->id }}</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">

    <h1>Бронь #{{ $booking->id }}</h1>

    @if (session('status'))
        <div style="padding: 12px; border: 1px solid #090; margin-bottom: 16px;">
            {{ session('status') }}
        </div>
    @endif

    <p><a href="{{ route('bookings.history') }}">← К истории</a></p>

    <p><b>Студия:</b> {{ $booking->hall->studio->name }}</p>
    <p><b>Зал:</b> {{ $booking->hall->name }}</p>

    <p><b>Интервал:</b>
        {{ $booking->start_at->format('Y-m-d H:i') }} → {{ $booking->end_at->format('Y-m-d H:i') }}
    </p>

    <p><b>Статус:</b> {{ $booking->status }}</p>

    <p><b>Цена зала:</b> {{ $booking->price_hall }} ₽</p>
    <p><b>Цена реквизита:</b> {{ $booking->price_equipment }} ₽</p>
    <p><b>Итого:</b> {{ $booking->price_total }} ₽</p>
    <p><b>Множитель:</b> {{ $booking->pricing_multiplier }}</p>

    <h3>Реквизит</h3>
    @if($booking->items->count() === 0)
        <p>—</p>
    @else
        <ul>
            @foreach($booking->items as $it)
                <li>
                    {{ $it->equipment->name }} × {{ $it->qty }}
                    ({{ $it->price_per_hour }} ₽/час за 1 шт)
                </li>
            @endforeach
        </ul>
    @endif

    <hr>

    <p>
        <a href="{{ route('bookings.pay.form', $booking) }}">Перейти к оплате (заглушка)</a>
    </p>

    @if(in_array($booking->status, ['awaiting_payment','failed'], true))
        <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
            @csrf
            <button type="submit">Отменить бронь</button>
        </form>
    @endif

    @if($booking->status === 'paid')
        <form method="POST" action="{{ route('bookings.refund', $booking) }}">
            @csrf
            <button type="submit">Сделать возврат (если разрешено правилами)</button>
        </form>
        <p style="color:#777;">
            Правило: ≤4 часа после оплаты и ≥2 часа до начала.
        </p>
    @endif

    @if($booking->refunds->count() > 0)
        <h3>Возвраты</h3>
        <ul>
            @foreach($booking->refunds as $r)
                <li>{{ $r->created_at->format('Y-m-d H:i') }} — {{ $r->status }} — {{ $r->amount }} ₽</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
