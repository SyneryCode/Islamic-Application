<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reciters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();           // رمز القارئ
            $table->string('mp3_code')->nullable();    // كود المجلد في سيرفر الصوت
            $table->string('name_ar');                 // الاسم بالعربية
            $table->string('name_en');                 // الاسم بالإنجليزية
            $table->string('style')->default('murattal'); // طريقة التلاوة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reciters');
    }
};
