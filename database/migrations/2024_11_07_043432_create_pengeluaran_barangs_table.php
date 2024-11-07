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
        Schema::create('jenis_pengeluaran_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 255);
            $table->timestamps();
        });
        Schema::create('master_pengeluaran_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_id')->constrained('jenis_pengeluaran_barangs')->onDelete('cascade'); // Foreign key
            $table->foreignId('supkonpro_id')->constrained('supkonpros')->onDelete('cascade'); // Foreign key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key
            $table->string('nama_pengambil', 255);
            $table->string('keterangan', 255);
            $table->timestamps();
        });
        Schema::create('detail_pengeluaran_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_pengeluaran_barang_id')->constrained('master_pengeluaran_barangs')->onDelete('cascade'); // Foreign key
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade'); // Foreign key
            $table->decimal('jumlah_keluar', 15, 2); 
            // $table->string('satuan_stok', 255);
            // $table->date('kadaluarsa')->nullable();
            $table->decimal('harga', 15, 2); 
            $table->decimal('total_harga', 15, 2); 
            $table->timestamps();
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pengeluaran_barangs');
        Schema::dropIfExists('master_pengeluaran_barangs');
        Schema::dropIfExists('detail_pengeluaran_barangs');

    }
};
