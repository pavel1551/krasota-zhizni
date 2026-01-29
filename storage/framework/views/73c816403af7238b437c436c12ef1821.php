<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Бронирование — <?php echo e($hall->name); ?></title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Бронирование: <?php echo e($hall->name); ?></h1>
    <p><a href="<?php echo e(route('halls.show', $hall)); ?>">← Назад</a></p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div style="padding: 12px; border: 1px solid #c00; margin-bottom: 16px;">
            <b>Ошибки:</b>
            <ul>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <li><?php echo e($e); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('bookings.store')); ?>" id="bookingForm">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="hall_id" value="<?php echo e($hall->id); ?>">

        <!-- Эти поля нужны БЭКЕНДУ, чтобы получить точный интервал брони.
             Пользователю показывать их не нужно, поэтому они скрытые. -->
        <input type="hidden" name="start_at" id="start_at" value="<?php echo e(old('start_at')); ?>">
        <input type="hidden" name="end_at" id="end_at" value="<?php echo e(old('end_at')); ?>">

        <h3>Выбор времени (календарь слотов)</h3>

        <div style="margin-bottom: 12px;">
            <label>Дата:</label><br>
            <input type="date" id="booking_date" required>
        </div>

        <div style="margin-bottom: 12px;">
            <label>Время начала (занятое скрывается):</label><br>
            <select id="start_time" required></select>
        </div>

        <div style="margin-bottom: 12px;">
            <label>Длительность (часы):</label><br>
            <select id="duration_hours" required>
                <option value="1">1 час</option>
                <option value="2">2 часа</option>
                <option value="3">3 часа</option>
                <option value="4">4 часа</option>
                <option value="5">5 часов</option>
                <option value="6">6 часов</option>
            </select>
        </div>

        <div id="timeInfo" style="padding: 10px; border: 1px dashed #999; margin-bottom: 16px;">
            Выберите дату и время — покажу доступность и реквизит.
        </div>

        <h3>Реквизит (ограничен по количеству)</h3>
        <p style="color:#555;">
            Ниже видно сколько свободно на выбранное время. Если 0 — покажу когда примерно освободится.
        </p>

        <div id="equipmentStatus" style="margin-bottom: 16px; padding: 10px; border: 1px solid #ddd;">
            Выберите время, чтобы увидеть свободное оборудование.
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $equipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div style="margin-bottom: 10px;">
                <b><?php echo e($eq->name); ?></b>
                (всего: <?php echo e($eq->total_qty); ?>, <?php echo e($eq->price_per_hour); ?> ₽/час за 1 шт)
                <br>
                <input type="number" min="0" max="50" name="equipment[<?php echo e($eq->id); ?>]" value="<?php echo e(old('equipment.' . $eq->id, 0)); ?>">
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <button type="submit" id="submitBtn">Создать бронь и перейти к оплате</button>
    </form>

    <hr>
    <p style="color:#777;">
        Важно: оплата — заглушка. В продакшене платежи делает платёжная система,
        а мы получаем результат через redirect/webhook.
    </p>

<script>
(function() {
    const apiAvailabilityUrl = "<?php echo e(route('api.halls.availability', $hall)); ?>";
    const apiEquipmentUrl = "<?php echo e(route('api.halls.equipment_availability', $hall)); ?>";

    const dateInput = document.getElementById('booking_date');
    const startSelect = document.getElementById('start_time');
    const durationSelect = document.getElementById('duration_hours');

    const startHidden = document.getElementById('start_at');
    const endHidden = document.getElementById('end_at');

    const timeInfo = document.getElementById('timeInfo');
    const equipmentStatus = document.getElementById('equipmentStatus');
    const submitBtn = document.getElementById('submitBtn');

    // Список занятых интервалов зала на выбранную дату
    // busyIntervals: [{ start: Date, end: Date }]
    let busyIntervals = [];

    // Запоминаем выбор пользователя, чтобы не сбрасывать время при смене длительности
    let lastSelectedTime = null;

    // Формируем список стартов по часу (08:00..22:00), шаг 60 минут
    function buildStartTimes() {
        const times = [];
        for (let h = 8; h <= 22; h++) {
            times.push(String(h).padStart(2,'0') + ':00');
        }
        return times;
    }

    // Создаём Date "по местному времени" (без Date.parse строк),
    // чтобы избежать сюрпризов с часовыми поясами.
    function makeLocalDate(dateStr, timeStr) {
        // dateStr: YYYY-MM-DD, timeStr: HH:MM
        const [Y,M,D] = dateStr.split('-').map(Number);
        const [h,m] = timeStr.split(':').map(Number);
        return new Date(Y, M-1, D, h, m, 0, 0);
    }

    // Добавляем часы к "локальной" дате
    function addHours(dt, hours) {
        const copy = new Date(dt.getTime());
        copy.setHours(copy.getHours() + Number(hours));
        return copy;
    }

    // Для hidden input datetime-local нужен формат YYYY-MM-DDTHH:MM
    function toDatetimeLocal(dt) {
        const yy = dt.getFullYear();
        const mm = String(dt.getMonth()+1).padStart(2,'0');
        const dd = String(dt.getDate()).padStart(2,'0');
        const hh = String(dt.getHours()).padStart(2,'0');
        const mi = String(dt.getMinutes()).padStart(2,'0');
        return `${yy}-${mm}-${dd}T${hh}:${mi}`;
    }

    // Пересечение интервалов: aStart < bEnd && aEnd > bStart
    function overlap(aStart, aEnd, bStart, bEnd) {
        return (aStart < bEnd) && (aEnd > bStart);
    }

    // ISO из API (Carbon toIso8601String) парсим нормально, там есть зона/offset
    function parseApiIso(iso) {
        return new Date(iso);
    }

    function hhmm(dt) {
        const hh = String(dt.getHours()).padStart(2,'0');
        const mm = String(dt.getMinutes()).padStart(2,'0');
        return `${hh}:${mm}`;
    }

    async function loadBusyForDate(dateStr) {
        const url = apiAvailabilityUrl + '?date=' + encodeURIComponent(dateStr);
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('Не удалось получить занятость зала');

        const data = await res.json();
        busyIntervals = (data.busy || []).map(x => ({
            start: parseApiIso(x.start_at),
            end: parseApiIso(x.end_at),
        }));
    }

    // Пересобираем список стартов и стараемся сохранить выбор пользователя
    function rebuildStartSelect(dateStr, preferredTime) {
        const allTimes = buildStartTimes();
        const duration = Number(durationSelect.value || 1);

        startSelect.innerHTML = '';

        allTimes.forEach(time => {
            const startDt = makeLocalDate(dateStr, time);
            const endDt = addHours(startDt, duration);

            let isBlocked = false;
            for (const b of busyIntervals) {
                if (overlap(startDt, endDt, b.start, b.end)) {
                    isBlocked = true;
                    break;
                }
            }

            const opt = document.createElement('option');
            opt.value = time;

            if (isBlocked) {
                opt.disabled = true;
                opt.textContent = time + ' (занято)';
            } else {
                opt.textContent = time;
            }

            startSelect.appendChild(opt);
        });

        // 1) Если preferredTime есть и он не занят — оставляем его
        if (preferredTime) {
            const candidate = Array.from(startSelect.options).find(o => o.value === preferredTime && !o.disabled);
            if (candidate) {
                startSelect.value = preferredTime;
                return;
            }
        }

        // 2) Иначе выбираем первый доступный
        const firstOk = Array.from(startSelect.options).find(o => !o.disabled);
        if (firstOk) startSelect.value = firstOk.value;
    }

    function updateHiddenInterval() {
        const dateStr = dateInput.value;
        const timeStr = startSelect.value;
        const duration = Number(durationSelect.value || 1);

        if (!dateStr || !timeStr) return;

        const startDt = makeLocalDate(dateStr, timeStr);
        const endDt = addHours(startDt, duration);

        startHidden.value = toDatetimeLocal(startDt);
        endHidden.value = toDatetimeLocal(endDt);

        // Пользователю показываем только человеко-понятный текст (без тех.данных)
        timeInfo.innerHTML = `<b>Выбран интервал:</b> ${dateStr} ${timeStr} → ${hhmm(endDt)}`;
    }

    async function loadEquipmentAvailability() {
        if (!startHidden.value || !endHidden.value) return;

        const url = apiEquipmentUrl
            + '?start_at=' + encodeURIComponent(startHidden.value)
            + '&end_at=' + encodeURIComponent(endHidden.value);

        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) {
            equipmentStatus.innerHTML = '<span style="color:#b00;">Ошибка получения реквизита</span>';
            return;
        }

        const data = await res.json();
        const list = data.equipment || [];

        let html = '<b>Свободное оборудование на выбранное время:</b><ul>';
        list.forEach(eq => {
            if (eq.free_qty > 0) {
                html += `<li>${eq.name}: <b>${eq.free_qty}</b> свободно из ${eq.total_qty}</li>`;
            } else {
                let when = 'нет данных, когда освободится';
                if (eq.next_free_at) {
                    const d = new Date(eq.next_free_at);
                    when = 'освободится примерно в <b>' + hhmm(d) + '</b>';
                }
                html += `<li>${eq.name}: <b>нет</b> на это время, ${when}</li>`;
            }
        });
        html += '</ul>';

        equipmentStatus.innerHTML = html;
    }

    async function refreshAll() {
        const dateStr = dateInput.value;
        if (!dateStr) return;

        submitBtn.disabled = true;
        equipmentStatus.innerHTML = 'Загружаю занятость и реквизит...';

        try {
            await loadBusyForDate(dateStr);

            // ВАЖНО: перед пересборкой сохраним текущий выбор
            // (если пользователь уже выбрал время)
            const current = startSelect.value || lastSelectedTime;

            rebuildStartSelect(dateStr, current);
            lastSelectedTime = startSelect.value;

            updateHiddenInterval();
            await loadEquipmentAvailability();

            // Если вообще нет доступных вариантов — блокируем отправку
            const hasAvailable = Array.from(startSelect.options).some(o => !o.disabled);
            submitBtn.disabled = !hasAvailable;

            if (!hasAvailable) {
                timeInfo.innerHTML = '<b>На выбранную дату нет свободного времени.</b>';
                equipmentStatus.innerHTML = 'Нет доступных слотов — реквизит не рассчитываю.';
            }
        } catch (e) {
            equipmentStatus.innerHTML = '<span style="color:#b00;">' + (e.message || 'Ошибка') + '</span>';
            submitBtn.disabled = true;
        }
    }

    // ===== Инициализация по умолчанию: завтра =====
    const now = new Date();
    now.setDate(now.getDate() + 1);
    const yy = now.getFullYear();
    const mm = String(now.getMonth()+1).padStart(2,'0');
    const dd = String(now.getDate()).padStart(2,'0');
    dateInput.value = `${yy}-${mm}-${dd}`;

    // ===== События =====
    dateInput.addEventListener('change', () => {
        lastSelectedTime = null;
        refreshAll();
    });

    durationSelect.addEventListener('change', () => {
        // НЕ сбрасываем время! пытаемся сохранить выбранное
        refreshAll();
    });

    startSelect.addEventListener('change', async () => {
        lastSelectedTime = startSelect.value;
        updateHiddenInterval();
        await loadEquipmentAvailability();
    });

    // ===== Первый запуск =====
    refreshAll();
})();
</script>

</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/bookings/create.blade.php ENDPATH**/ ?>