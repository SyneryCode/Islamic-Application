<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zakat_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zakat_type_id')->constrained('zakat_types')->cascadeOnDelete();
            
            // الحقول الجديدة
            $table->unsignedBigInteger('user_id')->nullable()->index(); // للمستخدمين لاحقًا
            $table->string('hijri_date')->nullable(); // التاريخ الهجري (YYYY-MM-DD)
            $table->string('fiqh_school')->default('default'); // المذهب الفقهي

            $table->decimal('amount', 18, 2);
            $table->decimal('nisab_value', 18, 2);
            $table->decimal('zakat_value', 18, 2);
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakat_calculations');
    }
};