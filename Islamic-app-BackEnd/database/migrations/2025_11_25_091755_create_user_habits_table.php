<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_habits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // العادة الأصلية (قالب) – اختياري، في حال كانت عادة خاصة بالمستخدم فقط
            $table->foreignId('habit_id')
                ->nullable()
                ->constrained('habits')
                ->nullOnDelete();

            // اسم العادة كما سيظهر للمستخدم (ننسخه من القالب أو ندخله مباشرة)
            $table->string('name');

            // مدة التنفيذ بالأيام
            $table->unsignedInteger('duration_days');

            // تاريخ بداية العادة
            $table->date('start_date');

            // تاريخ إنجاز العادة (لو أنجزها المستخدم)
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_habits');
    }
};
