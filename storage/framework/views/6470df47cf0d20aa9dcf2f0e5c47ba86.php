<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Панель управления — Красота-Жизни</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">
    <h1>Панель управления</h1>

    <p>Вы вошли в систему как: <b><?php echo e(auth()->user()->name); ?></b></p>

    <ul>
        <li><a href="<?php echo e(route('halls.index')); ?>">Залы фотостудии</a></li>
        <li><a href="<?php echo e(route('bookings.history')); ?>">Мои бронирования</a></li>
        <li>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit">Выйти</button>
            </form>
        </li>
    </ul>

    <hr>
    <p style="color:#777;">
        Это демонстрационная “панель управления” для проекта бронирования фотостудии «Красота-Жизни».
    </p>
</body>
</html>
<?php /**PATH C:\Users\ProSistema\krasota-zhizni\resources\views/dashboard.blade.php ENDPATH**/ ?>