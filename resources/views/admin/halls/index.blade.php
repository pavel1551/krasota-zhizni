<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Залы — админка</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Залы (админ)</h1>

    <p>
        <a href="{{ route('admin.dashboard') }}">← Назад в админку</a> |
        <a href="{{ route('admin.halls.create') }}">+ Добавить зал</a>
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
            <th>База ₽/час</th>
            <th>Активен</th>
            <th>Действия</th>
        </tr>

        @foreach($halls as $h)
            <tr>
                <td>{{ $h->id }}</td>
                <td>{{ $h->studio?->name }}</td>
                <td><b>{{ $h->name }}</b></td>
                <td>{{ $h->base_price_per_hour }}</td>
                <td>{{ $h->is_active ? 'Да' : 'Нет' }}</td>
                <td>
                    <a href="{{ route('admin.halls.edit', $h) }}">Редактировать</a>

                    <form method="POST" action="{{ route('admin.halls.destroy', $h) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Удалить зал?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
