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
        Schema::table('anggaran_histories', function (Blueprint $table) {
            $table->decimal('jumlah', 15, 2)->after('limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('anggaran_histories', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });
    }
};
