<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Админка — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Админ-панель: «Красота-Жизни»</h1>

    <p>Вы вошли как администратор: <b>{{ auth()->user()->name }}</b></p>

    <ul>
        <li><a href="{{ route('admin.dashboard') }}">Статистика</a></li>
        <li><a href="{{ route('admin.schedule') }}">Занятость залов (расписание)</a></li>
        <li><a href="{{ route('admin.halls.index') }}">Залы (управление)</a></li>
        <li><a href="{{ route('admin.equipment.index') }}">Реквизит (управление)</a></li>
        <li><a href="{{ route('halls.index') }}">Перейти в пользовательскую часть</a></li>
    </ul>

    <hr>

    <h2>Статистика за период</h2>

    <form method="GET" action="{{ route('admin.dashboard') }}">
        <label>С:</label>
        <input type="date" name="from" value="{{ $fromDate }}">
        <label>По:</label>
        <input type="date" name="to" value="{{ $toDate }}">
        <button type="submit">Показать</button>
    </form>

    <div style="margin-top: 16px; padding: 12px; border: 1px solid #ddd;">
        <p><b>Выручка (gross):</b> {{ $grossRevenue }} ₽</p>
        <p><b>Возвраты:</b> {{ $refundAmount }} ₽ (кол-во: {{ $refundCount }})</p>
        <p><b>Чистая прибыль (net):</b> {{ $netProfit }} ₽</p>
        <p><b>Оплаченных броней:</b> {{ $paidCount }}</p>
    </div>

    <hr>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Выйти</button>
    </form>

    <p style="color:#777;">
        Примечание: статистика демонстрационная. В продакшене часто считают по данным платёжного провайдера и бухучёта.
    </p>
</body>
</html>
