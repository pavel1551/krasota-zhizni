<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($brand ?? 'Красота-Жизни'); ?></title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Фотостудия «<?php echo e($brand ?? 'Красота-Жизни'); ?>»</h1>

    <p>
        Демонстрационный MVP системы бронирования: динамическая цена, реквизит с ограничением количества,
        возврат по правилам и заглушка оплаты.
    </p>

    <hr>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <p>
                Вы вошли в систему как: <b><?php echo e(auth()->user()->name); ?></b>
            </p>

            <ul>
                <li><a href="<?php echo e(route('dashboard')); ?>">Панель управления</a></li>
                <li><a href="<?php echo e(route('halls.index')); ?>">Посмотреть залы</a></li>
                <li><a href="<?php echo e(route('bookings.history')); ?>">Мои бронирования</a></li>
            </ul>

            <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin-top: 16px;">
                <?php echo csrf_field(); ?>
                <button type="submit">Выйти</button>
            </form>
        <?php else: ?>
            <ul>
                <li><a href="<?php echo e(route('halls.index')); ?>">Посмотреть залы</a></li>
                <li>
                    <a href="<?php echo e(route('login')); ?>">Войти</a>
                    /
                    <a href="<?php echo e(route('register')); ?>">Регистрация</a>
                </li>
            </ul>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php else: ?>
        <p style="color:#b00;">
            Роуты авторизации не подключены (login/register не найдены).
            Проверь, что в routes/web.php есть строка: require __DIR__.'/auth.php';
        </p>

        <ul>
            <li><a href="<?php echo e(route('halls.index')); ?>">Посмотреть залы</a></li>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/welcome.blade.php ENDPATH**/ ?>