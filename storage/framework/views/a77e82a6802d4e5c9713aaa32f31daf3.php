<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Бронь #<?php echo e($booking->id); ?></title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">

    <h1>Бронь #<?php echo e($booking->id); ?></h1>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div style="padding: 12px; border: 1px solid #090; margin-bottom: 16px;">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <p><a href="<?php echo e(route('bookings.history')); ?>">← К истории</a></p>

    <p><b>Студия:</b> <?php echo e($booking->hall->studio->name); ?></p>
    <p><b>Зал:</b> <?php echo e($booking->hall->name); ?></p>

    <p><b>Интервал:</b>
        <?php echo e($booking->start_at->format('Y-m-d H:i')); ?> → <?php echo e($booking->end_at->format('Y-m-d H:i')); ?>

    </p>

    <p><b>Статус:</b> <?php echo e($booking->status); ?></p>

    <p><b>Цена зала:</b> <?php echo e($booking->price_hall); ?> ₽</p>
    <p><b>Цена реквизита:</b> <?php echo e($booking->price_equipment); ?> ₽</p>
    <p><b>Итого:</b> <?php echo e($booking->price_total); ?> ₽</p>
    <p><b>Множитель:</b> <?php echo e($booking->pricing_multiplier); ?></p>

    <h3>Реквизит</h3>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->items->count() === 0): ?>
        <p>—</p>
    <?php else: ?>
        <ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $booking->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <li>
                    <?php echo e($it->equipment->name); ?> × <?php echo e($it->qty); ?>

                    (<?php echo e($it->price_per_hour); ?> ₽/час за 1 шт)
                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <hr>

    <p>
        <a href="<?php echo e(route('bookings.pay.form', $booking)); ?>">Перейти к оплате (заглушка)</a>
    </p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($booking->status, ['awaiting_payment','failed'], true)): ?>
        <form method="POST" action="<?php echo e(route('bookings.cancel', $booking)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit">Отменить бронь</button>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->status === 'paid'): ?>
        <form method="POST" action="<?php echo e(route('bookings.refund', $booking)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit">Сделать возврат (если разрешено правилами)</button>
        </form>
        <p style="color:#777;">
            Правило: ≤4 часа после оплаты и ≥2 часа до начала.
        </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->refunds->count() > 0): ?>
        <h3>Возвраты</h3>
        <ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $booking->refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <li><?php echo e($r->created_at->format('Y-m-d H:i')); ?> — <?php echo e($r->status); ?> — <?php echo e($r->amount); ?> ₽</li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/bookings/show.blade.php ENDPATH**/ ?>