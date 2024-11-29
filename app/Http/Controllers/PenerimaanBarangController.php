<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\DetailPenerimaanBarang;
use Illuminate\Support\Facades\Auth;
use App\Models\JenisPenerimaan;
use App\Models\supkonpro;
use Illuminate\Http\Request;
use App\Models\PenerimaanBarang;
use App\Models\User;
use Illuminate\Support\Facades\DB; 

class PenerimaanBarangController extends Controller
{
    public function loadAllPenerimaanBarang(){
        $all_detail_penerimaans = DetailPenerimaanBarang::all();
        $all_master_penerimaans = PenerimaanBarang::all();
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_penerimaans = JenisPenerimaan::all();
        
        return view('barang-masuk.index',compact('all_master_penerimaans', 
                    'all_detail_penerimaans','all_supkonpros', 'all_users', 'all_jenis_penerimaans'));
    }
    
    public function MasterBarangMasukSearch(Request $request)
    {
        $query = $request->input('query');

        $all_master_penerimaans = PenerimaanBarang::whereHas('supkonpro', function ($q) use ($query) {
                $q->where('nama', 'like', "%$query%");
            })
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->orWhereHas('jenisPenerimaanBarang', function ($q) use ($query) {
                $q->where('jenis', 'like', "%$query%");
            })
            ->orWhere('nama_pengantar', 'like', "%$query%")
            ->orWhere('keterangan', 'like', "%$query%")
            ->get();

        return view('barang-masuk.index', compact('all_master_penerimaans'));
    }


    public function loadAddBarangMasukForm()
    {
        $all_master_penerimaans = PenerimaanBarang::all();
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_penerimaans = JenisPenerimaan::all();
        $user = Auth::user(); 
        $all_barangs = barang::all();
        
        return view('barang-masuk.add-barang-masuk', compact(
            'all_master_penerimaans', 'all_supkonpros', 'all_users', 'all_jenis_penerimaans', 'user',
            'all_barangs'
        ));
    }


    public function addBarangMasuk(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis_id' => 'required',
            'supkonpro_id' => 'required',
            'user_id' => 'required|exists:users,id',
            'nama_pengantar' => 'required|string',
            'barang_id' => 'required|array',
            'jumlah_diterima' => 'required|array',
            'harga' => 'required|array',
            'total_harga' => 'required|array',
            'tanggal' => 'required|date',
            'invoice' => 'required|string',  // Pastikan invoice diterima
        ]);

        // Membuat PenerimaanBarang
        $barangMasuk = new PenerimaanBarang();
        $barangMasuk->jenis_id = $request->jenis_id;
        $barangMasuk->supkonpro_id = $request->supkonpro_id;
        $barangMasuk->nama_pengantar = $request->nama_pengantar;
        $barangMasuk->tanggal = $request->tanggal;
        $barangMasuk->invoice = $request->invoice;
        $barangMasuk->keterangan = $request->keterangan ?? '';
        $barangMasuk->user_id = $request->user_id;
        $barangMasuk->save();

        // Insert detail barang
        foreach ($request->barang_id as $key => $barangId) {
            $detail = new DetailPenerimaanBarang();
            $detail->master_penerimaan_barang_id = $barangMasuk->id;
            $detail->barang_id = $barangId;
            $detail->jumlah_diterima = $request->jumlah_diterima[$key];
            $detail->harga = str_replace(',', '', $request->harga[$key]);
            $detail->total_harga = str_replace(',', '', $request->total_harga[$key]);
            $detail->save();
        }
       // Loop through each barang_id in the request
        foreach ($request->barang_id as $key => $barangId) {
            $barang = Barang::findOrFail($barangId);  // Fetch each barang by its ID
            $barang->stok += $request->jumlah_diterima[$key];  // Increment the stock by the quantity received
            $barang->save();  // Save the updated stock back to the database
        }

        return redirect()->route('master-barang-masuk')->with('success', 
                                 'Barang Masuk berhasil ditambahkan.');
    }

    public function generateInvoicePenerimaan(Request $request)
    {
            $tanggal = $request->tanggal;

            // Ambil tanggal dalam format Y-m-d
            $date = \Carbon\Carbon::parse($tanggal);

            // Ambil berapa banyak penerimaan barang yang sudah ada pada tanggal tersebut
            $count = PenerimaanBarang::whereDate('created_at', $date->toDateString())->count();

            // Nomor urut dimulai dari 1
            $noUrut = str_pad($count + 1, 2, '0', STR_PAD_LEFT);  // Contoh: 01, 02, ...

            return response()->json(['noUrut' => $noUrut]);
    }


    public function deleteMasterBarang($id)
    {
        try {
            // Begin a transaction to ensure data consistency
            DB::beginTransaction();
            
            // Retrieve all associated detail records
            $details = DetailPenerimaanBarang::where('master_penerimaan_barang_id', $id)->get();

            // Iterate over each detail to adjust the stock
            foreach ($details as $detail) {
                $barang = Barang::find($detail->barang_id);

                if ($barang) {
                    // Deduct the jumlah_diterima from the stock
                    $barang->stok -= $detail->jumlah_diterima;
                    $barang->save();
                }
            }

            // Delete the detail records associated with this master record
            DetailPenerimaanBarang::where('master_penerimaan_barang_id', $id)->delete();
            
            // Delete the master PenerimaanBarang record
            PenerimaanBarang::where('id', $id)->delete();
            
            // Commit the transaction
            DB::commit();
            
            return redirect('/master-barang-masuk')->with('success', 'Deleted successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            return redirect('/master-barang-masuk')->with('fail', $e->getMessage());
        }
    }


    public function detailMasterBarang($id)
    {
        $master_penerimaan = PenerimaanBarang::findOrFail($id);
        $supkonpro = supkonpro::findOrFail($master_penerimaan->supkonpro_id);
        $user = User::findOrFail($master_penerimaan->user_id);
        $jenis_penerimaan = JenisPenerimaan::findOrFail($master_penerimaan->jenis_id);
        $detail_penerimaan = DetailPenerimaanBarang::where('master_penerimaan_barang_id', $id)->get();

        return view('barang-masuk.detail-barang-masuk', compact(
            'master_penerimaan', 'supkonpro', 'user', 'jenis_penerimaan', 'detail_penerimaan',
        ));
    }
    
    public function loadAllDetailPenerimaanBarang(){
        $all_detail_penerimaans= DetailPenerimaanBarang::all();
        $all_master_penerimaans = PenerimaanBarang::all();
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_penerimaans = JenisPenerimaan::all();
        $all_barangs = barang::all();
        
        return view('barang-masuk.index-detail',compact('all_detail_penerimaans',
                    'all_master_penerimaans', 'all_supkonpros', 
                    'all_users', 'all_jenis_penerimaans', 'all_barangs'));
    }
    
    public function DetailBarangMasukSearch(Request $request)
    {
        $query = $request->input('query');

        $all_detail_penerimaans = DetailPenerimaanBarang::whereHas('PenerimaanBarang', function ($q) use ($query) {
                $q->where('id', 'like', "%$query%");
            })
            ->orWhereHas('barang', function ($q) use ($query) {
                $q->where('nama', 'like', "%$query%");
            })
            ->orWhere('jumlah_diterima', 'like', "%$query%")
            ->orWhere('harga', 'like', "%$query%")
            ->orWhere('total_harga', 'like', "%$query%")
            ->get();

        return view('barang-masuk.index-detail', compact('all_detail_penerimaans'));
    }

    public function loadAllJenisPenerimaanBarang(){
        $all_jenis_penerimaans= JenisPenerimaan::all();
        
        return view('barang-masuk.jenis-barang-masuk',compact('all_jenis_penerimaans'));
    }

    public function loadAddJenisBarangMasukForm()
    {
        return view('barang-masuk.add-jenis-barang-masuk');
    }

    public function AddJenisBarangMasuk(Request $request){
        $request->validate([
            'jenis' => 'required|string',
        ]);

        try {
            $new_jenisBarangMasuk = new JenisPenerimaan();
            $new_jenisBarangMasuk->jenis = $request->jenis;
            $new_jenisBarangMasuk->save();

            return redirect('/jenis-barang-masuk/')->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            return redirect('/jenis-barang-masuk')->with('fail', $e->getMessage());
        }
    }

    public function deleteJenisBarangMasuk($id){
        try {
            JenisPenerimaan::where('id',$id)->delete();
            return redirect('/jenis-barang-masuk')->with('success','Deleted successfully!');
        } catch (\Exception $e) {
            return redirect('/jenis-barang-masuk')->with('fail',$e->getMessage());
            
        }
    }

    public function loadEditJenisBarangMasukForm($id)
    {
        $JenisPenerimaan = JenisPenerimaan::findOrFail($id);
        return view('barang-masuk.edit-jenis-barang-masuk', compact('JenisPenerimaan'));
    }

    public function EditJenisBarangMasuk(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'jenis' => 'required|string',
            'jenis_id' => 'required|integer' // Pastikan jenis_id diterima
        ]);

        try {
            // Lakukan update berdasarkan jenis_id
            $update_jenisBarangMasuk = JenisPenerimaan::where('id', $request->jenis_id)->update([
                'jenis' => $request->jenis,
            ]);

            return redirect('/jenis-barang-masuk')->with('success', 'Edit Successfully');
        } catch (\Exception $e) {
            return redirect('/jenis-barang-masuk')->with('fail', $e->getMessage());
        }
    }

    public function loadEditBarangMasukForm($id)
    {
        $masterPenerimaan = PenerimaanBarang::with('detailpenerimaanbarang')->findOrFail($id);
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_penerimaans = JenisPenerimaan::all();
        $user = Auth::user(); 
        $all_barangs = barang::all();
        $detail_penerimaan = DetailPenerimaanBarang::all();
        
        return view('barang-masuk.edit-barang-masuk', compact(
            'masterPenerimaan', 'all_supkonpros', 'all_users', 'all_jenis_penerimaans', 'user',
            'all_barangs', 'detail_penerimaan'
        ));
    }

    public function EditPenerimaanBarang(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'masterPenerimaan_id' => 'required|exists:master_penerimaan_barangs,id',
            'jenis_id' => 'required|exists:jenis_penerimaan_barangs,id',
            'supkonpro_id' => 'required|exists:supkonpros,id',
            'nama_pengantar' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_diterima' => 'required',
            'harga' => 'required',  
            'total_harga' => 'required', 
        ]);

        $harga = str_replace('.', '', $request->input('harga'));
        $total_harga = str_replace('.', '', $request->input('total_harga'));

        try {
            $detailPenerimaanBarang = DetailPenerimaanBarang::where('master_penerimaan_barang_id', 
                $request->masterPenerimaan_id)
                ->where('barang_id', $request->barang_id)
                ->firstOrFail();

          
            $selisihJumlah = $request->jumlah_diterima - $detailPenerimaanBarang->jumlah_diterima;

            
            PenerimaanBarang::where('id', $request->masterPenerimaan_id)->update([
                'jenis_id' => $request->jenis_id,
                'supkonpro_id' => $request->supkonpro_id,
                'nama_pengantar' => $request->nama_pengantar,
                'keterangan' => $request->keterangan,
            ]);

            $detailPenerimaanBarang->update([
                'jumlah_diterima' => $request->jumlah_diterima,
                'harga' => $harga,
                'total_harga' => $total_harga,
            ]);

            $barang = Barang::findOrFail($request->barang_id);
            $barang->stok += $selisihJumlah;
            $barang->save();

            return redirect('/master-barang-masuk/')->with('success', 'Edit Successfully');
        } catch (\Exception $e) {
            return redirect('/edit-penerimaan-barang/' . $request->masterPenerimaan_id)->with('fail', $e->getMessage());
        }
    }
}
