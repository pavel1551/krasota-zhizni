<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Оплата (заглушка) — бронь #<?php echo e($booking->id); ?></title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Оплата (ЗАГЛУШКА) — бронь #<?php echo e($booking->id); ?></h1>

    <p><a href="<?php echo e(route('bookings.show', $booking)); ?>">← К брони</a></p>

    <p><b>Статус:</b> <?php echo e($booking->status); ?></p>
    <p><b>Резерв до:</b> <?php echo e($booking->reserved_until?->format('Y-m-d H:i') ?? '—'); ?></p>

    <h3>Сумма к оплате: <?php echo e($booking->price_total); ?> ₽</h3>

    <p style="color:#777;">
        Это демонстрация. В реальном проекте здесь был бы редирект на платёжный шлюз,
        а результат приходил бы от платёжной системы.
    </p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->status !== 'awaiting_payment'): ?>
        <p><b>Оплата недоступна для этого статуса.</b></p>
    <?php else: ?>
        <form method="POST" action="<?php echo e(route('bookings.pay', $booking)); ?>">
            <?php echo csrf_field(); ?>

            <div style="margin-bottom: 12px;">
                <label>Держатель карты (для демонстрации, шифруется):</label><br>
                <input type="text" name="card_holder" value="<?php echo e(old('card_holder')); ?>">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Последние 4 цифры карты (для демонстрации):</label><br>
                <input type="text" name="card_last4" maxlength="4" value="<?php echo e(old('card_last4')); ?>">
            </div>

            <p><b>Выбери результат оплаты:</b></p>

            <button type="submit" name="outcome" value="success">✅ Успешная оплата</button>
            <button type="submit" name="outcome" value="failed">❌ Ошибка оплаты</button>
            <button type="submit" name="outcome" value="timeout">⏳ Таймаут (долго ждали)</button>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/bookings/pay.blade.php ENDPATH**/ ?>