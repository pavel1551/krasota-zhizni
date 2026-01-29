<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Добавить зал — админка</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Добавить зал</h1>
    <p><a href="{{ route('admin.halls.index') }}">← Назад</a></p>

    @if ($errors->any())
        <div style="padding: 12px; border: 1px solid #c00; margin-bottom: 16px;">
            <b>Ошибки:</b>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.halls.store') }}">
        @csrf

        <div>
            <label>Студия:</label><br>
            <select name="studio_id" required>
                @foreach($studios as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-top:10px;">
            <label>Название:</label><br>
            <input type="text" name="name" required value="{{ old('name') }}">
        </div>

        <div style="margin-top:10px;">
            <label>Базовая цена/час (₽):</label><br>
            <input type="number" name="base_price_per_hour" min="0" required value="{{ old('base_price_per_hour', 2000) }}">
        </div>

        <div style="margin-top:10px;">
            <label>Описание:</label><br>
            <textarea name="description" rows="4" cols="50">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top:10px;">
            <label>
                <input type="checkbox" name="is_active" value="1" checked>
                Активен
            </label>
        </div>

        <button style="margin-top:14px;" type="submit">Создать</button>
    </form>
</body>
</html>
