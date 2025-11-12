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
        Schema::create('rencana_aksi_6_tahuns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->references('id')->on('penggunas')->onDelete('cascade');
            $table->foreignId('id_subprogram')->references('id')->on('subprograms')->onDelete('cascade');
            $table->string('rencana_aksi');
            $table->longText('kegiatan');
            $table->longText('sub_kegiatan');
            $table->longText('nama_program');
            $table->string('lokasi');
            $table->string('volume');
            $table->string('satuan');
            $table->string('anggaran');
            $table->string('sumberdana');
            $table->string('tahun');

            $table->foreignId('id_opd')
                ->constrained('opds')
                ->onDelete('cascade');
            $table->longText('keterangan')->nullable();
            $table->enum('delete_at', ['0', '1'])->default('0')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_aksi_6_tahuns');
    }
};
