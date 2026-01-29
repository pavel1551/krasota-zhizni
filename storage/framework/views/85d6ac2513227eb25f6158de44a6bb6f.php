<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Залы — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Залы фотостудии</h1>

    <p><a href="<?php echo e(route('home')); ?>">← На главную</a></p>

    <ul>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $halls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hall): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <li style="margin-bottom: 12px;">
                <b><?php echo e($hall->name); ?></b>
                (<?php echo e($hall->studio->name); ?>) —
                <?php echo e($hall->base_price_per_hour); ?> ₽/час
                <br>
                <a href="<?php echo e(route('halls.show', $hall)); ?>">Открыть</a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    | <a href="<?php echo e(route('bookings.create', $hall)); ?>">Забронировать</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </li>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </ul>
</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/halls/index.blade.php ENDPATH**/ ?>