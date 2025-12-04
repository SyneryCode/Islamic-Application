<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZakatType;

class ZakatTypeSeeder extends Seeder
{
    public function run(): void
    {
        ZakatType::upsert([
            [
                'key'         => 'money',
                'name'        => 'زكاة المال',
                'description' => 'الأموال النقدية والذهب والفضة وما في حكمها'
            ],
            [
                'key'         => 'assets',
                'name'        => 'زكاة الأصول',
                'description' => 'الأصول الاستثمارية غير التجارية'
            ],
            [
                'key'         => 'trade',
                'name'        => 'زكاة التجارة',
                'description' => 'البضائع المعدّة للبيع وما في حكمها'
            ],
        ], ['key'], ['name', 'description']);
    }
}