<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyiraman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanam_id')
                ->constrained('tanam')
                ->cascadeOnDelete();
            $table->time('jam');
            $table->enum('repeat', [
                'everyday',
                'sunday',
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
            ]);
            $table->float('jumlah_air');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyiraman');
    }
};
