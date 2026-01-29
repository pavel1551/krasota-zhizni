<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Hall;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Создаём студию (бренд)
        $studio = Studio::query()->firstOrCreate(
            ['slug' => 'krasota-zhizni'],
            [
                'name' => 'Красота-Жизни',
                'address' => 'г. Саратов, ул. Примерная, 10',
                'phone' => '+7 (900) 000-00-00',
            ]
        );

        // 2) Залы
        Hall::query()->firstOrCreate(
            ['studio_id' => $studio->id, 'name' => 'Зал “Минимализм”'],
            ['description' => 'Светлый зал с циклорамой.', 'base_price_per_hour' => 2000, 'capacity' => 6]
        );

        Hall::query()->firstOrCreate(
            ['studio_id' => $studio->id, 'name' => 'Зал “Неон”'],
            ['description' => 'Неоновые источники света, тёмные фоны.', 'base_price_per_hour' => 2500, 'capacity' => 4]
        );

        // 3) Реквизит / оборудование (ограничено по количеству)
        Equipment::query()->firstOrCreate(
            ['studio_id' => $studio->id, 'name' => 'Камера Canon R6'],
            ['description' => 'Беззеркальная камера', 'total_qty' => 2, 'price_per_hour' => 600, 'is_active' => true]
        );

        Equipment::query()->firstOrCreate(
            ['studio_id' => $studio->id, 'name' => 'Объектив 24-70'],
            ['description' => 'Универсальный зум', 'total_qty' => 3, 'price_per_hour' => 300, 'is_active' => true]
        );

        Equipment::query()->firstOrCreate(
            ['studio_id' => $studio->id, 'name' => 'Постоянный свет (LED панель)'],
            ['description' => 'Свет для видео и фото', 'total_qty' => 5, 'price_per_hour' => 200, 'is_active' => true]
        );

        // 4) Пользователь-админ для демонстрации
        User::query()->firstOrCreate(
            ['email' => 'admin@krasota.test'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'is_admin' => true]
        );
    }
}
