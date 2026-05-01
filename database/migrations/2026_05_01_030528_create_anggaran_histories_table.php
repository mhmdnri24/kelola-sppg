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
        Schema::create('anggaran_histories', function (Blueprint $table) {
            $table->id();            
            $table->date('date');
            $table->enum('trans_type',['IN','OUT'])->default('IN');
            $table->enum('status',['draft','cancel','release'])->default('draft');
            $table->foreignId('dapur_id')->constrained('dapurs')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('pagu',15,2)->default('0');
            $table->decimal('limit',15,2)->default('0');
            $table->string('module');
            $table->string('notes');
            $table->integer('trans_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_histories');
    }
};
