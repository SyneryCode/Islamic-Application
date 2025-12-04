<?php

namespace Database\Seeders;

use App\Models\Habit;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        $habits = [
            [
                'name'                  => 'أذكار الصباح',
                'description'           => 'قراءة أذكار الصباح يومياً بعد الفجر أو بعد الشروق.',
                'default_duration_days' =>1,
            ],
            [
                'name'                  => 'أذكار المساء',
                'description'           => 'قراءة أذكار المساء يومياً بعد العصر أو بعد المغرب.',
                'default_duration_days' => 1,
            ],
            [
                'name'                  => 'صلاة الوتر',
                'description'           => 'المداومة على صلاة الوتر كل ليلة.',
                'default_duration_days' => 1,
            ],
            [
                'name'                  => 'صيام الإثنين ',
                'description'           => 'صيام يوم الإثنين من كل أسبوع',
                'default_duration_days' => 7,
            ],
            [
                'name'                  => 'صيام الخميس ',
                'description'           => 'صيام يوم الخميس من كل أسبوع',
                'default_duration_days' => 7,
            ],
            [
                'name'                  => 'قراءة جزء من القرآن',
                'description'           => 'قراءة جزء واحد من القرآن يومياً.',
                'default_duration_days' => 1,
            ],
        ];

        foreach ($habits as $habit) {
            Habit::create($habit);
        }
    }
}
