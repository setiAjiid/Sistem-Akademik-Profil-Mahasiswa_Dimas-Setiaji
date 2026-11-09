<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel mahasiswa. is_active false berarti mahasiswa sedang cuti akademik.
     */
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nrp', 10)->unique();
            $table->string('nama', 100)->index();
            $table->foreignId('departemen_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('angkatan');
            $table->decimal('ipk', 3, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
