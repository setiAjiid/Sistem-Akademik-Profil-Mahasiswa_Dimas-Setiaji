<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel tugas kuliah. Nama tabelnya "tugas" (bukan "tugases").
     */
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150)->index();
            $table->text('deskripsi')->nullable();
            $table->foreignId('departemen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dosen_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tenggat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
