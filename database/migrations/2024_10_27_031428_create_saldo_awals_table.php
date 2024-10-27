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
            $table->id(); // Primary key: 'id' (BIGINT)
            $table->unsignedBigInteger('barang_id'); // Foreign key: 'barang_id' (BIGINT)
            $table->year('tahun'); // 'tahun' (YEAR)
            $table->unsignedTinyInteger('bulan'); // 'bulan' (MONTH as TinyInteger)
            $table->decimal('saldo_awal', 15, 2); // 'saldo_awal' (DECIMAL with 15 digits and 2 decimal places)
            $table->decimal('total_terima', 15, 2); // 'total_terima' (DECIMAL)
            $table->decimal('total_keluar', 15, 2); // 'total_keluar' (DECIMAL)
            $table->decimal('saldo_akhir', 15, 2); // 'saldo_akhir' (DECIMAL)
            $table->timestamps(); // 'created_at' and 'updated_at' (TIMESTAMP)

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
