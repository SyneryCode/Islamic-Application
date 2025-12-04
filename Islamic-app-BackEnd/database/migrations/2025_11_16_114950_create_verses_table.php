<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surah_id')->constrained()->cascadeOnDelete();
            $table->unsignedMediumInteger('verse_number'); // رقم الآية
            $table->text('text_ar'); // نص الآية (نسخة عثمانية)
            $table->unsignedSmallInteger('page_number')->nullable();// رقم الصفحة في مصحف المدينة
            $table->unsignedTinyInteger('juz_number')->nullable(); // رقم الجزء (1-30)
            $table->unsignedTinyInteger('hizb_number')->nullable(); // رقم الحزب
            $table->unsignedTinyInteger('hizb_quarter')->nullable(); // ربع الحزب (1-4)
            $table->fullText('text_ar'); // لتحسين البحث (MySQL 5.7+)
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index(['surah_id', 'verse_number']);
            $table->index('page_number');
            $table->index('juz_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verses');
    }
};