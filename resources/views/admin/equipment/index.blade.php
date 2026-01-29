<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Реквизит — админка</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Реквизит (админ)</h1>

    <p>
        <a href="{{ route('admin.dashboard') }}">← Назад в админку</a> |
        <a href="{{ route('admin.equipment.create') }}">+ Добавить реквизит</a>
    </p>

    @if (session('status'))
        <div style="padding: 10px; border: 1px solid #0a0; margin-bottom: 12px;">
            {{ session('status') }}
        </div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Студия</th>
            <th>Название</th>
            <th>Кол-во</th>
            <th>Цена/час</th>
            <th>Активен</th>
            <th>Действия</th>
        </tr>

        @foreach($equipment as $eq)
            <tr>
                <td>{{ $eq->id }}</td>
                <td>{{ $eq->studio?->name }}</td>
                <td><b>{{ $eq->name }}</b></td>
                <td>{{ $eq->total_qty }}</td>
                <td>{{ $eq->price_per_hour }} ₽</td>
                <td>{{ $eq->is_active ? 'Да' : 'Нет' }}</td>
                <td>
                    <a href="{{ route('admin.equipment.edit', $eq) }}">Редактировать</a>

                    <form method="POST" action="{{ route('admin.equipment.destroy', $eq) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Удалить реквизит?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
