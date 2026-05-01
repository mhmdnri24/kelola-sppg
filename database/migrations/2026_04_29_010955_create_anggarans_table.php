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
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->foreignId('dapur_id')
                ->constrained()
                ->onDelete('restrict');
            $table->enum('kategori', ['3B', 'SISWA', 'UMUM'])->default('UMUM');
            $table->string('nama_anggaran');
            $table->integer('pm_pb')->default(0);
            $table->integer('pm_pk')->default(0);
            $table->decimal('pagu_pb', 15, 2)->default(0);
            $table->decimal('pagu_pk', 15, 2)->default(0);
            $table->decimal('hpp_pb', 15, 2)->default(0);
            $table->decimal('hpp_pk', 15, 2)->default(0);
            $table->date('active_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
