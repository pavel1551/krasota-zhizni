<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Мои бронирования</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Мои бронирования</h1>

    <p>
        <a href="<?php echo e(route('home')); ?>">← На главную</a> |
        <a href="<?php echo e(route('halls.index')); ?>">Залы</a>
    </p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookings->count() === 0): ?>
        <p>Бронирований пока нет.</p>
    <?php else: ?>
        <ul>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <li style="margin-bottom: 14px;">
                    <b>#<?php echo e($b->id); ?></b> — <?php echo e($b->status); ?> —
                    <?php echo e($b->hall->name); ?> —
                    <?php echo e($b->start_at->format('Y-m-d H:i')); ?> → <?php echo e($b->end_at->format('Y-m-d H:i')); ?>

                    — <?php echo e($b->price_total); ?> ₽
                    <br>
                    <a href="<?php echo e(route('bookings.show', $b)); ?>">Открыть</a>
                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/bookings/history.blade.php ENDPATH**/ ?>