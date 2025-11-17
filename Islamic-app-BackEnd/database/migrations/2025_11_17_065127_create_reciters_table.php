<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reciters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();    // مثال: ar.alafasy
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('style')->nullable(); // mujawwad / murattal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reciters');
    }
};
