<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Админ — Расписание — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 24px;">

<h1>Админ-панель — Расписание занятости</h1>

<p style="color:#444; margin-top: 4px;">
    Здесь администратор видит занятость залов фотостудии «Красота-Жизни»:
    по часам, с привязкой к пользователям и статусам бронирований.
</p>

<hr>

<!-- Верхнее меню -->
<div style="display:flex; gap: 18px; align-items:center; flex-wrap: wrap;">
    <a href="{{ route('dashboard') }}">Панель</a>
    <a href="{{ route('halls.index') }}">Залы (пользователь)</a>
    <a href="{{ route('bookings.history') }}">Мои бронирования</a>

    <!-- Если у тебя есть отдельные админские роуты — замени на свои -->
    @if (\Illuminate\Support\Facades\Route::has('admin.equipment.index'))
        <a href="{{ route('admin.equipment.index') }}">Реквизит (админ)</a>
    @endif

    @if (\Illuminate\Support\Facades\Route::has('admin.halls.index'))
        <a href="{{ route('admin.halls.index') }}">Залы (админ)</a>
    @endif

    @if (\Illuminate\Support\Facades\Route::has('admin.stats.index'))
        <a href="{{ route('admin.stats.index') }}">Статистика</a>
    @endif

    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit">Выйти</button>
    </form>
</div>

<hr>

<!-- Форма выбора даты -->
<form method="GET" style="margin-bottom: 16px;">
    <label><b>Дата:</b></label>
    <input type="date" name="date" value="{{ $date ?? now()->toDateString() }}">
    <button type="submit">Показать</button>
</form>

@php
    /**
     * Дальше строим таблицу занятости:
     * - строки: залы
     * - столбцы: часы (например 08:00..22:00)
     * - ячейка: свободно или занято (с данными брони)
     *
     * ВАЖНО: это "демо-таблица", сделана максимально понятно.
     * В реальном проекте лучше строить карту занятости в контроллере.
     */

    $selectedDate = $date ?? now()->toDateString();

    $fromHour = isset($workFromHour) ? (int)$workFromHour : 8;
    $toHour   = isset($workToHour) ? (int)$workToHour : 23;

    // Строим список слотов "час начала" (например 08:00..22:00), потому что бронь может перекрывать час
    $slots = [];
    for ($h = $fromHour; $h <= $toHour - 1; $h++) {
        $slots[] = str_pad((string)$h, 2, '0', STR_PAD_LEFT) . ':00';
    }

    // Безопасно получаем брони
    $bookingsList = $bookings ?? collect();

    // Функция пересечения интервалов (в минутах)
    $overlap = function(\Carbon\Carbon $aStart, \Carbon\Carbon $aEnd, \Carbon\Carbon $bStart, \Carbon\Carbon $bEnd): bool {
        return $aStart->lt($bEnd) && $aEnd->gt($bStart);
    };

    // Цвета по статусу (простая визуализация)
    $statusColor = function(string $status): string {
        return match ($status) {
            'paid' => '#d2ffd2',            // оплачено
            'awaiting_payment' => '#fff3bf', // ожидает оплату (резерв)
            'failed' => '#ffd6d6',          // оплата не прошла
            'canceled' => '#eeeeee',        // отменено
            'refunded' => '#d6f0ff',        // возвращено
            'expired' => '#f2e6ff',         // просрочено
            default => '#f7f7f7',
        };
    };

    $statusText = function(string $status): string {
        return match ($status) {
            'paid' => 'Оплачено',
            'awaiting_payment' => 'Резерв (ожид.)',
            'failed' => 'Оплата не прошла',
            'canceled' => 'Отменено',
            'refunded' => 'Возврат',
            'expired' => 'Просрочено',
            default => $status,
        };
    };
@endphp

<!-- Легенда -->
<div style="margin: 10px 0; padding: 10px; border:1px solid #ddd;">
    <b>Легенда статусов:</b>
    <div style="display:flex; gap: 10px; flex-wrap: wrap; margin-top: 8px;">
        <span style="background:#d2ffd2; padding:4px 8px; border:1px solid #ccc;">Оплачено</span>
        <span style="background:#fff3bf; padding:4px 8px; border:1px solid #ccc;">Резерв (ожид. оплаты)</span>
        <span style="background:#ffd6d6; padding:4px 8px; border:1px solid #ccc;">Оплата не прошла</span>
        <span style="background:#eeeeee; padding:4px 8px; border:1px solid #ccc;">Отменено</span>
        <span style="background:#d6f0ff; padding:4px 8px; border:1px solid #ccc;">Возврат</span>
        <span style="background:#f2e6ff; padding:4px 8px; border:1px solid #ccc;">Просрочено</span>
    </div>
</div>

@if (!isset($halls) || (isset($halls) && $halls->count() === 0))
    <div style="padding: 12px; border: 1px solid #c00;">
        Нет залов. Админ не может построить расписание.
    </div>
@else
    <div style="overflow:auto; border:1px solid #ddd;">
        <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; min-width: 980px;">
            <thead>
            <tr style="background:#f3f3f3;">
                <th style="position: sticky; left:0; background:#f3f3f3; z-index:2;">Зал</th>
                @foreach($slots as $slot)
                    <th style="white-space:nowrap;">{{ $slot }}</th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($halls as $hall)
                <tr>
                    <!-- Левый столбец (зал) фиксируем, чтобы удобно скроллить -->
                    <td style="position: sticky; left:0; background:#fff; z-index:1; min-width: 240px;">
                        <b>{{ $hall->name }}</b><br>
                        <small style="color:#666;">
                            @if(isset($hall->studio))
                                {{ $hall->studio->name }}
                            @endif
                        </small>
                    </td>

                    @foreach($slots as $slot)
                        @php
                            // Строим slotStart и slotEnd на выбранной дате
                            $slotStart = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $selectedDate.' '.$slot);
                            $slotEnd = $slotStart->copy()->addHour();

                            // Ищем первую бронь, которая пересекается с этим часовым слотом
                            // В демо достаточно первой, но можно показывать число пересечений.
                            $hit = null;

                            foreach ($bookingsList as $b) {
                                if ((int)$b->hall_id !== (int)$hall->id) continue;

                                // start_at/end_at должны быть Carbon (если в модели casts = datetime)
                                $bStart = $b->start_at instanceof \Carbon\Carbon ? $b->start_at : \Carbon\Carbon::parse($b->start_at);
                                $bEnd   = $b->end_at   instanceof \Carbon\Carbon ? $b->end_at   : \Carbon\Carbon::parse($b->end_at);

                                if ($overlap($slotStart, $slotEnd, $bStart, $bEnd)) {
                                    $hit = $b;
                                    break;
                                }
                            }
                        @endphp

                        @if($hit)
                            @php
                                $bg = $statusColor((string)$hit->status);
                                $st = $statusText((string)$hit->status);

                                $uName = '';
                                if (isset($hit->user) && $hit->user) {
                                    $uName = $hit->user->name;
                                } else {
                                    $uName = 'Пользователь #' . (int)$hit->user_id;
                                }

                                $range = '';
                                try {
                                    $range = $hit->start_at->format('H:i') . '–' . $hit->end_at->format('H:i');
                                } catch (\Throwable $e) {
                                    $range = '';
                                }
                            @endphp

                            <td style="background: {{ $bg }}; vertical-align: top; min-width: 120px;">
                                <div style="font-size: 12px; line-height: 1.2;">
                                    <b>{{ $st }}</b><br>
                                    <span style="color:#333;">{{ $range }}</span><br>
                                    <span style="color:#555;">{{ $uName }}</span><br>

                                    <!-- Ссылка на бронь: если есть админский роут — используем, иначе обычный -->
                                    @if(\Illuminate\Support\Facades\Route::has('admin.bookings.show'))
                                        <a href="{{ route('admin.bookings.show', $hit) }}">Открыть</a>
                                    @elseif(\Illuminate\Support\Facades\Route::has('bookings.show'))
                                        <a href="{{ route('bookings.show', $hit) }}">Открыть</a>
                                    @endif
                                </div>
                            </td>
                        @else
                            <td style="background:#fff; color:#777; text-align:center;">
                                свободно
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <p style="margin-top: 12px; color:#666;">
        Подсказка: если бронь начинается в середине часа, этот час тоже считается “занятым”, потому что есть пересечение интервалов.
        Это нормальная логика расписания.
    </p>
@endif

</body>
</html>
