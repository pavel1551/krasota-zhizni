<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo e($hall->name); ?> — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1><?php echo e($hall->name); ?></h1>
    <p><a href="<?php echo e(route('halls.index')); ?>">← Назад к залам</a></p>

    <p><b>Студия:</b> <?php echo e($hall->studio->name); ?></p>
    <p><b>Цена:</b> <?php echo e($hall->base_price_per_hour); ?> ₽/час (база, далее множитель)</p>
    <p><b>Описание:</b> <?php echo e($hall->description ?? '—'); ?></p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <p>
            <a href="<?php echo e(route('bookings.create', $hall)); ?>">Забронировать этот зал</a>
        </p>
    <?php else: ?>
        <p>
            Чтобы бронировать:
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
                <a href="<?php echo e(route('login')); ?>">войдите</a>.
            <?php else: ?>
                <a href="<?php echo e(route('home')); ?>">перейдите на главную</a>.
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/halls/show.blade.php ENDPATH**/ ?>