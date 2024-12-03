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
        Schema::create('jenis_penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 255);
            $table->timestamps();
        });
        Schema::create('master_penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('invoice', 255);
            $table->date('tanggal');
            $table->foreignId('jenis_id')->constrained('jenis_penerimaan_barangs')->onDelete('cascade'); // Foreign key
            $table->foreignId('supkonpro_id')->constrained('supkonpros')->onDelete('cascade'); // Foreign key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key
            $table->string('nama_pengantar', 255);
            $table->string('keterangan', 255);
            $table->decimal('harga_invoice', 15, 2); 
            $table->timestamps();
        });
        Schema::create('detail_penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_penerimaan_barang_id')->constrained('master_penerimaan_barangs')->onDelete('cascade'); // Foreign key
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade'); // Foreign key
            $table->decimal('jumlah_diterima', 15, 2); 
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
        Schema::dropIfExists('jenis_penerimaan_barangs');
        Schema::dropIfExists('master_penerimaan_barangs');
        Schema::dropIfExists('detail_penerimaan_barangs');

    }
};
