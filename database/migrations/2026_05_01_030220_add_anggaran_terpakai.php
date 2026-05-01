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
        //
        Schema::table('anggarans',function(Blueprint $table){
            $table->decimal('anggaran_terpakai',15,2)->default(0);
            $table->decimal('anggaran_sisa',15,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
          Schema::table('anggarans',function(Blueprint $table){
            $table->dropColumn('anggaran_terpakai');
            $table->dropColumn('anggaran_sisa');
        });
    }
};
