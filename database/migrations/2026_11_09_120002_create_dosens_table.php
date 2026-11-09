<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel dosen. NIP dan nama diberi indeks karena sering dipakai untuk pencarian.
     */
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 18)->unique();
            $table->string('nama', 100)->index();
            $table->string('email')->nullable();
            $table->foreignId('departemen_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
