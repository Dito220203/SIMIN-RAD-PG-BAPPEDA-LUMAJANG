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
        Schema::create('foto_progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_monev')->references('id')->on('monevs')->onDelete('cascade');
            $table->foreignId('id_pengguna')->references('id')->on('penggunas')->onDelete('cascade');
           $table->string('foto')->nullable();
             $table->longText('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_progres');
    }
};
