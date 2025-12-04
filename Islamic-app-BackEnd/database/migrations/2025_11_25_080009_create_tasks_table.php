<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // اسم المهمة (مثلاً: أذكار الصباح، أذكار المساء، صلاة، صيام...)
            $table->string('name');

            // مدة المهمة بالأيام
            $table->unsignedInteger('duration_days');

            // تاريخ بداية المهمة
            $table->date('start_date');

            // وقت إنجاز المهمة (لو أنجزها المستخدم قبل انتهاء المدة)
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
