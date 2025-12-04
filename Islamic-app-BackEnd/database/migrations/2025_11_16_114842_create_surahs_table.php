<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('number')->unique(); // رقم السورة (1-114)
            $table->string('name_ar'); // الاسم العربي (الفاتحة)
            $table->string('name_en'); // الاسم الإنجليزي (Al-Fatiha)
            $table->string('revelation_place'); // مكان النزول (مكية/مدنية)
            $table->unsignedSmallInteger('verses_count'); // عدد الآيات
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};