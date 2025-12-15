<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tanam', function (Blueprint $table) {
            $table->id(); // id tanam (auto increment)

            $table->foreignId('kebun_id')
                ->constrained('kebun')
                ->cascadeOnDelete();

            $table->string('jenis_tanaman');
            $table->integer('jumlah_tanaman');
            $table->date('tanggal_tanam');

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanam');
    }
};
