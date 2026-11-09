<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel rencana proyek kelompok AI yang diisi mahasiswa dan dinilai dosen atau asdos.
     */
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            // Pembuat proposal. Kalau akunnya dihapus, proposalnya ikut terhapus.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul', 120);
            $table->text('deskripsi');
            // Diisi dosen atau asdos. Kosong berarti belum dinilai.
            $table->unsignedTinyInteger('nilai')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dinilai_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
