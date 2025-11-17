<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('juzs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('juz_number')->unique(); // رقم الجزء (1-30)
            $table->foreignId('start_surah_id')->constrained('surahs');
            $table->unsignedMediumInteger('start_verse_number'); // أول آية في الجزء
            $table->foreignId('end_surah_id')->constrained('surahs');
            $table->unsignedMediumInteger('end_verse_number'); // آخر آية في الجزء
            $table->unsignedSmallInteger('start_page'); // أول صفحة في الجزء
            $table->unsignedSmallInteger('end_page'); // آخر صفحة في الجزء
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('juzs');
    }
};