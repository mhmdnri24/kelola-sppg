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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('supplier_type', ['business', 'individual'])->default('business');      
                $table->unsignedBigInteger('partner_supplier_id')->nullable();
                $table->foreign('partner_supplier_id')->references('id')->on('suppliers')->onDelete('set null');      
            $table->string('logo')->nullable();
            $table->string('contact_person')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
