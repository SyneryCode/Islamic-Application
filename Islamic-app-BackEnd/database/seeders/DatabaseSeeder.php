<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $this->call([
            SurahsSeeder::class,
            ZakatTypeSeeder::class,
            // QuranTextToVersesSeeder::class,        // إذا عندك
            RecitersSeeder::class,
            QuranFullSeeder::class,
            // أضف أي Seeders أخرى هنا
        ]);

        User::create([
    'username' => 'test',
    'email' => 'test@example.com',
    'password' => 'password',
]);

    }
}
