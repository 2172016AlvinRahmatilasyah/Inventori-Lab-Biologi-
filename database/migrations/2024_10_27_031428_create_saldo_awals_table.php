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
        Schema::create('saldo_awals', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('barang_id'); 
            $table->year('tahun'); 
            $table->unsignedTinyInteger('bulan'); 
            $table->decimal('saldo_awal', 15, 2); 
            $table->decimal('total_terima', 15, 2); 
            $table->decimal('total_keluar', 15, 2); 
            $table->decimal('saldo_akhir', 15, 2); 
            $table->timestamps(); 

            // Foreign key constraint
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_awals');
    }
};
