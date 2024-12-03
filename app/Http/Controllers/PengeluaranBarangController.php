<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\barang;
use App\Models\DetailPengeluaranBarang;
use App\Models\supkonpro;
use Illuminate\Http\Request;
use App\Models\JenisPengeluaran;
use App\Models\PengeluaranBarang;
use App\Models\pengeluaran_barang;
use App\Models\SaldoAwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Testing\Fakes\PendingMailFake;
use Illuminate\Support\Facades\DB; 


class PengeluaranBarangController extends Controller
{
 
    public function loadAllPengeluaranBarang(Request $request)
    {
        // Ambil jumlah item per halaman dari request, default 5
        $perPage = $request->input('perPage', 5);
        
        // Mengambil data berdasarkan pagination
        $all_detail_pengeluarans = detailPengeluaranBarang::orderBy('created_at', 'desc')
            ->paginate($perPage)  // Apply pagination based on 'perPage'
            ->appends(request()->except('page')); // Maintain other query parameters like search or filters

        // Ambil data untuk dropdown
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_pengeluarans = JenisPengeluaran::all();
        $all_master_pengeluarans = PengeluaranBarang::all();

        // Return view dengan data yang sudah dipaginasi
        return view('barang-keluar.index',compact('all_master_pengeluarans', 'all_supkonpros', 
                     'all_detail_pengeluarans','all_users', 'all_jenis_pengeluarans'));
    }

    public function BarangKeluarSearch(Request $request)
    {
        // Ambil query pencarian dari input
        $query = $request->input('query');
        
        // Menentukan jumlah item per halaman (pagination)
        $perPage = $request->input('perPage', 5);

        // Lakukan pencarian berdasarkan berbagai field yang diinginkan
        $all_detail_pengeluarans = detailPengeluaranBarang::whereHas('pengeluaranBarang', function ($q) use ($query) {
            // Pencarian pada tabel master_penerimaan_barangs
            $q->where('invoice', 'like', "%$query%")
                ->orWhere('tanggal', 'like', "%$query%")
                ->orWhere('keterangan', 'like', "%$query%")
                ->orWhereHas('supkonpro', function ($q) use ($query) {
                    $q->where('nama', 'like', "%$query%");  // Nama supkonpro
                })
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");  // Nama user
                })
                ->orWhereHas('jenispengeluaranbarang', function ($q) use ($query) {
                    $q->where('jenis', 'like', "%$query%"); 
                })
                ->orWhere('nama_pengambil', 'like', "%$query%"); 
        })
        ->orWhereHas('barang', function ($q) use ($query) {
            $q->where('nama_barang', 'like', "%$query%");  // Pencarian di tabel barang
        })
        
        ->orWhere('jumlah_keluar', 'like', "%$query%")
        ->orWhere('harga', 'like', "%$query%")
        ->orWhere('total_harga', 'like', "%$query%")
        ->paginate($perPage)  // Menggunakan paginate untuk hasil pencarian
        ->appends(request()->except('page')); // Menjaga parameter lain seperti search atau filters

        return view('barang-keluar.index', compact('all_detail_pengeluarans'));
    }


    public function loadAddBarangKeluarForm()
    {
        $all_master_pengeluarans = PengeluaranBarang::all();
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_pengeluarans = JenisPengeluaran::all();
        $user = Auth::user(); 
        $all_barangs = barang::all();
        
        return view('barang-keluar.add-barang-keluar', compact(
            'all_master_pengeluarans', 'all_supkonpros', 'all_users', 'all_jenis_pengeluarans', 'user',
            'all_barangs'
        ));
    }

    public function addBarangKeluar(Request $request)
    {
        $request->validate([
            'jenis_id' => 'required',
            'supkonpro_id' => 'required',
            'user_id' => 'required|exists:users,id',
            'nama_pengambil' => 'required|string',
            'barang_id' => 'required|array',
            'jumlah_keluar' => 'required|array',
            'harga' => 'required|array',
            'total_harga' => 'required|array',
            'tanggal' => 'required|date',
            'invoice' => 'required|string',
            'keterangan' => 'required|string',
            'harga_invoice' => 'required',
        ]);

        // Menyimpan data pengeluaran barang
        $barangKeluar = new PengeluaranBarang();
        $barangKeluar->jenis_id = $request->jenis_id;
        $barangKeluar->supkonpro_id = $request->supkonpro_id;
        $barangKeluar->nama_pengambil = $request->nama_pengambil;
        $barangKeluar->tanggal = $request->tanggal;
        $barangKeluar->invoice = $request->invoice;
        $barangKeluar->keterangan = $request->keterangan;
        $barangKeluar->user_id = $request->user_id;
        $barangKeluar->harga_invoice = str_replace(',', '', $request->harga_invoice);
        $barangKeluar->save();

        // Menyimpan detail pengeluaran barang dan mengurangi stok
        foreach ($request->barang_id as $key => $barangId) {
            $detail = new detailPengeluaranBarang();
            $detail->master_pengeluaran_barang_id = $barangKeluar->id;
            $detail->barang_id = $barangId;
            $detail->jumlah_keluar = $request->jumlah_keluar[$key];
            $detail->harga = str_replace(',', '', $request->harga[$key]);
            $detail->total_harga = str_replace(',', '', $request->total_harga[$key]);
            $detail->save();

            // Update stok barang
            $barang = Barang::findOrFail($barangId);
            $barang->stok -= $request->jumlah_keluar[$key];
            $barang->save();
            
            // Update saldo_awals berdasarkan bulan dan barang_id
            $tanggal = \Carbon\Carbon::parse($request->tanggal);
            $bulan = $tanggal->month;  // Ambil bulan dari tanggal pengeluaran
            $tahun = $tanggal->year;   // Ambil tahun dari tanggal pengeluaran

            // Cek apakah saldo_awals sudah ada untuk bulan dan tahun tersebut dan barang_id yang sama
            $saldoAwal = SaldoAwal::where('barang_id', $barangId)
                                ->where('bulan', $bulan)
                                ->where('tahun', $tahun)  // Pastikan juga sesuai dengan tahun
                                ->first();

            if ($saldoAwal) {
                // Jika saldo_awals sudah ada, update saldo_terima
                $saldoAwal->total_keluar += str_replace(',', '', $request->jumlah_keluar[$key]);
                $saldoAwal->saldo_akhir -= str_replace(',', '', $request->jumlah_keluar[$key]);
                $saldoAwal->save();
            } else {
                // Jika saldo_awals belum ada, buat saldo baru
                $saldoAwal = new SaldoAwal();
                $saldoAwal->barang_id = $barangId;
                $saldoAwal->bulan = $bulan;
                $saldoAwal->tahun = $tahun;  // Set tahun
                $saldoAwal->total_keluar = str_replace(',', '', $request->jumlah_keluar[$key]);
                $saldoAwal->saldo_awal = 0; 
                $saldoAwal->total_terima = 0; 
                $saldoAwal->saldo_akhir -=  $saldoAwal->total_keluar; 
                $saldoAwal->save();
            }
        }

        return redirect()->route('master-barang-keluar')->with('success', 'Barang Keluar berhasil ditambahkan.');
    }

    public function generateInvoicePengeluaran(Request $request)
        {
            $tanggal = $request->tanggal;

            // Ambil tanggal dalam format Y-m-d
            $date = \Carbon\Carbon::parse($tanggal);

            $count = PengeluaranBarang::whereDate('tanggal', $date->toDateString())->count();

            // Nomor urut dimulai dari 1
            $noUrut2 = str_pad($count + 1, 2, '0', STR_PAD_LEFT);  // Contoh: 01, 02, ...

            return response()->json(['noUrut' => $noUrut2]);
        }

    public function deletePengeluaranBarang($id)
    {
         try {
                // Begin a transaction to ensure data consistency
                DB::beginTransaction();
        
                // Find the detail pengeluaran barang record
                $detail = DetailPengeluaranBarang::findOrFail($id);
        
                // Find the corresponding master pengeluaran barang
                $masterPengeluaran = PengeluaranBarang::findOrFail($detail->master_pengeluaran_barang_id);
        
                // Adjust the stock of the corresponding barang
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok += $detail->jumlah_keluar;  // Increase stock by jumlah_keluar (because we're deleting)
                    $barang->save();  // Save updated stock
                }
        
                // Delete the detail record
                $detail->delete();
        
                // Now, adjust the total_terima and total_keluar on saldo_awals table
                // Get the correct month and year from master pengeluaran barang
                $tanggalMaster = \Carbon\Carbon::parse($masterPengeluaran->tanggal);
                $bulan = $tanggalMaster->month;
                $tahun = $tanggalMaster->year;
        
                // Find the saldo_awal record based on barang_id, bulan, and tahun
                $saldoAwal = SaldoAwal::where('barang_id', $detail->barang_id)
                    ->where('bulan', $bulan)  // Correctly use month from master pengeluaran
                    ->where('tahun', $tahun)  // Correctly use year from master pengeluaran
                    ->first();
        
                if ($saldoAwal) {
                    // Subtract jumlah_keluar from total_keluar (as we're deleting the record)
                    $saldoAwal->total_keluar -= $detail->jumlah_keluar;
        
                    // Recalculate saldo_akhir: total_terima - total_keluar
                    $saldoAwal->saldo_akhir = $saldoAwal->total_terima - $saldoAwal->total_keluar;
        
                    // Save the updated saldo_awal record
                    $saldoAwal->save();
                }
        
                // Commit the transaction
                DB::commit();
        
                return redirect('/master-barang-keluar')->with('success', 'Detail pengeluaran barang berhasil dihapus.');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();
                return redirect('/master-barang-keluar')->with('fail', 'Gagal menghapus detail pengeluaran barang: ' . $e->getMessage());
            }   
    }    
        
        
    public function detailPengeluaranBarang($id)
    {
        $master_pengeluaran = PengeluaranBarang::findOrFail($id);
        $supkonpro = supkonpro::findOrFail($master_pengeluaran->supkonpro_id);
        $user = User::findOrFail($master_pengeluaran->user_id);
        $jenis_pengeluaran = JenisPengeluaran::findOrFail($master_pengeluaran->jenis_id);
        $detail_pengeluaran = DetailPengeluaranBarang::where('master_pengeluaran_barang_id', $id)->get();

        return view('barang-keluar.detail-barang-keluar', compact(
            'master_pengeluaran', 'supkonpro', 'user', 'jenis_pengeluaran', 'detail_pengeluaran',
        ));
    }
    
    public function loadAllDetailPengeluaranBarang(){
        $all_detail_pengeluarans= DetailPengeluaranBarang::all();
        $all_master_pengeluarans = PengeluaranBarang::all();
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_pengeluaran = JenisPengeluaran::all();
        $all_barangs = barang::all();
        
        return view('barang-keluar.index-detail',compact('all_detail_pengeluarans',
                    'all_master_pengeluarans', 'all_supkonpros', 
                    'all_users', 'all_jenis_pengeluaran', 'all_barangs'));
    }
    
    public function DetailBarangKeluarSearch(Request $request)
    {
        $query = $request->input('query');

        $all_detail_pengeluarans = detailPengeluaranBarang::whereHas('PengeluaranBarang', function ($q) use ($query) {
                $q->where('id', 'like', "%$query%");
            })
            ->orWhereHas('barang', function ($q) use ($query) {
                $q->where('nama', 'like', "%$query%");
            })
            ->orWhere('jumlah_keluar', 'like', "%$query%")
            ->orWhere('harga', 'like', "%$query%")
            ->orWhere('total_harga', 'like', "%$query%")
            ->get();

        return view('barang-keluar.index-detail', compact('all_detail_pengeluarans'));
    }

    public function loadAllJenisPengeluaranBarang(){
        $all_jenis_pengeluarans= JenisPengeluaran::all();
        
        return view('barang-keluar.jenis-barang-keluar',compact('all_jenis_pengeluarans'));
    }

    public function loadAddJenisBarangKeluarForm()
    {
        return view('barang-keluar.add-jenis-barang-keluar');
    }

    public function AddJenisBarangKeluar(Request $request){
        $request->validate([
            'jenis' => 'required|string',
        ]);

        try {
            $new_jenisBarangKeluar = new JenisPengeluaran();
            $new_jenisBarangKeluar->jenis = $request->jenis;
            $new_jenisBarangKeluar->save();

            return redirect('/jenis-barang-keluar/')->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            return redirect('/jenis-barang-keluar')->with('fail', $e->getMessage());
        }
    }

    public function deleteJenisBarangKeluar($id){
        try {
            JenisPengeluaran::where('id',$id)->delete();
            return redirect('/jenis-barang-keluar')->with('success','Deleted successfully!');
        } catch (\Exception $e) {
            return redirect('/jenis-barang-keluar')->with('fail',$e->getMessage());
            
        }
    }

    public function loadEditJenisBarangKeluarForm($id)
    {
        $JenisPengeluaran = JenisPengeluaran::findOrFail($id);
        return view('barang-keluar.edit-jenis-barang-keluar', compact('JenisPengeluaran'));
    }

    public function EditJenisBarangKeluar(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string',
            'jenis_id' => 'required|integer'
        ]);

        try {
            $update_jenisBarangKeluar = JenisPengeluaran::where('id', $request->jenis_id)->update([
                'jenis' => $request->jenis,
            ]);

            return redirect('/jenis-barang-keluar')->with('success', 'Edit Successfully');
        } catch (\Exception $e) {
            return redirect('/jenis-barang-keluar')->with('fail', $e->getMessage());
        }
    }

    public function loadEditBarangKeluarForm($id)
    {
        $detail_pengeluaran = DetailPengeluaranBarang::findOrFail($id);
        $masterPengeluaran = $detail_pengeluaran->pengeluaranBarang;
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_pengeluarans = JenisPengeluaran::all();
        $user = Auth::user(); 
        $all_barangs = barang::all();
        
        return view('barang-keluar.edit-barang-keluar', compact(
            'masterPengeluaran', 'all_supkonpros', 'all_users', 'all_jenis_pengeluarans', 'user',
            'all_barangs', 'detail_pengeluaran'
        ));
    }

    public function EditPengeluaranBarang(Request $request)
    {   
        $request->validate([
            'masterPengeluaran_id' => 'required|exists:master_pengeluaran_barangs,id',
            'detail_pengeluaran_id' => 'required|exists:detail_pengeluaran_barangs,id',
            'tanggal' => 'required|exists:master_pengeluaran_barangs,tanggal',
            'invoice' => 'required|exists:master_pengeluaran_barangs,invoice',
            'jenis_id' => 'required|exists:jenis_pengeluaran_barangs,id',
            'supkonpro_id' => 'required|exists:supkonpros,id',
            'nama_pengambil' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_keluar' => 'required',
            'harga' => 'required',  
            'total_harga' => 'required', 
        ]);

        $harga = str_replace('.', '', $request->input('harga'));
        $total_harga = str_replace('.', '', $request->input('total_harga'));

        try {
            // Ambil data detail pengeluaran lama
            $detailPengeluaranBarang = DetailPengeluaranBarang::findOrFail($request->detail_pengeluaran_id);

            // Hitung selisih jumlah keluar
            $selisihJumlah = $request->jumlah_keluar - $detailPengeluaranBarang->jumlah_keluar;
            $selisihTotalHarga = $total_harga - $detailPengeluaranBarang->total_harga; // Hitung selisih total_harga

            // Update pengeluaran master
            PengeluaranBarang::where('id', $detailPengeluaranBarang->master_pengeluaran_barang_id)->update([
                'tanggal' => $request->tanggal,
                'invoice' => $request->invoice,
                'jenis_id' => $request->jenis_id,
                'supkonpro_id' => $request->supkonpro_id,
                'nama_pengambil' => $request->nama_pengambil,
                'keterangan' => $request->keterangan,
            ]);
        
            // Update detail pengeluaran barang
            $detailPengeluaranBarang->update([
                'jumlah_keluar' => $request->jumlah_keluar,
                'harga' => $harga,
                'total_harga' => $total_harga,
            ]);

            // Update stok barang sesuai selisih jumlah keluar
            $barang = Barang::findOrFail($request->barang_id);
            $barang->stok -= $selisihJumlah;
            $barang->save();

            // Update saldo_terima pada saldo_awals
            $tanggal = \Carbon\Carbon::parse($request->tanggal);
            $bulan = $tanggal->month;  // Ambil bulan dari tanggal pengeluaran

            // Cek apakah saldo_awals sudah ada untuk bulan tersebut dan barang_id yang sama
            $saldoAwal = SaldoAwal::where('barang_id', $request->barang_id)
                                ->where('bulan', $bulan)
                                ->first();

            if ($saldoAwal) {
                // Update saldo_awals berdasarkan selisih
                $saldoAwal->total_keluar += $selisihJumlah; // Tambahkan selisih total_harga
                $saldoAwal->saldo_akhir = $saldoAwal->saldo_awal + $saldoAwal->total_terima - $saldoAwal->total_keluar;
                // Tambahkan selisih total_harga
                $saldoAwal->save();
            }

            return redirect('/master-barang-keluar/')->with('success', 'Edit Successfully');
        } catch (\Exception $e) {
            return redirect('/edit-pengeluaran-barang/' . $request->detail_pengeluaran_id)->with('fail', $e->getMessage());
        }
    }

}
