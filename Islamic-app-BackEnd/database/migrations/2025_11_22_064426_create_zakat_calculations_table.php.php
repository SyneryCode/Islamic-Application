<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('zakat_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zakat_type_id')->constrained('zakat_types')->cascadeOnDelete();

            $table->decimal('amount', 18, 2);        // مال، أصول، تجارة
            $table->decimal('nisab_value', 18, 2);   // 85g * gold_price
            $table->decimal('zakat_value', 18, 2);   // الناتج النهائي

            $table->json('details')->nullable();     // breakdown
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('zakat_calculations');
    }
};
