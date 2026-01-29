<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Редактировать реквизит — админка</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Редактировать реквизит</h1>
    <p><a href="{{ route('admin.equipment.index') }}">← Назад</a></p>

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

    <form method="POST" action="{{ route('admin.equipment.update', $equipment) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Студия:</label><br>
            <select name="studio_id" required>
                @foreach($studios as $s)
                    <option value="{{ $s->id }}" @if($equipment->studio_id == $s->id) selected @endif>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-top:10px;">
            <label>Название:</label><br>
            <input type="text" name="name" required value="{{ old('name', $equipment->name) }}">
        </div>

        <div style="margin-top:10px;">
            <label>Кол-во (шт):</label><br>
            <input type="number" name="total_qty" min="0" required value="{{ old('total_qty', $equipment->total_qty) }}">
        </div>

        <div style="margin-top:10px;">
            <label>Цена/час (₽):</label><br>
            <input type="number" name="price_per_hour" min="0" required value="{{ old('price_per_hour', $equipment->price_per_hour) }}">
        </div>

        <div style="margin-top:10px;">
            <label>
                <input type="checkbox" name="is_active" value="1" @if(old('is_active', $equipment->is_active)) checked @endif>
                Активен
            </label>
        </div>

        <button style="margin-top:14px;" type="submit">Сохранить</button>
    </form>
</body>
</html>
