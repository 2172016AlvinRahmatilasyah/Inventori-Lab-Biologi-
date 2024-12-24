<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\barang;
use App\Models\supkonpro;
use App\Models\jenis_barang;
use Illuminate\Http\Request;
use App\Models\JenisPenerimaan;
use App\Models\PenerimaanBarang;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPengeluaranBarang;
use App\Models\JenisPengeluaran;
use App\Models\PengeluaranBarang;

class BarangController extends Controller
{
    
    public function loadAllBarangs(Request $request) {
        $perPage = $request->input('perPage', 10);
        $jenisBarangId = $request->input('jenisBarang'); // Ambil filter jenis barang
        $jenis_barangs = jenis_barang::all(); // Ambil semua jenis barang untuk dropdown
    
        // Query untuk mengambil barang dengan filter jenis barang
        $query = Barang::with('jenisBarang'); // Mengambil data barang beserta relasi jenisBarang
    
        if ($jenisBarangId) {
            // Filter barang berdasarkan jenis barang
            $query->whereHas('jenisBarang', function($q) use ($jenisBarangId) {
                $q->where('id', $jenisBarangId);
            });
        }
    
        $all_barangs = $query->paginate($perPage)->appends(request()->except('page')); // Menampilkan barang dengan pagination dan mempertahankan parameter query
    
        return view('kelola-barang.index', compact('all_barangs', 'jenis_barangs'));
    }
    
    public function loadAddBarangForm(){
        $jenis_barangs = jenis_barang::all();
        return view('kelola-barang.add-barang', compact('jenis_barangs'));
    }

    public function AddBarang(Request $request){
        // perform form validation here
        $request->validate([
            'brand' => 'required|string',
            'nama_barang' => 'required|string',
            'no_catalog' => 'required|string',
            'jenis_barang_id' => 'required|exists:jenis_barangs,id', 
            'stok' => ['required', 'gt:0'], 
            'kadaluarsa' => 'nullable|date',
            'lokasi' => 'required|string',
            'status_barang' => 'nullable|string',
            'plate' => 'nullable|string',
        ]);

        try {
            // Buat objek baru untuk barang
            $new_barang = new Barang;
            $new_barang->brand = $request->brand;
            $new_barang->nama_barang = $request->nama_barang;
            $new_barang->no_catalog = $request->no_catalog;
            $new_barang->jenis_barang_id = $request->jenis_barang_id; // Ambil dari input
            $new_barang->stok = $request->stok;
            $new_barang->kadaluarsa = $request->kadaluarsa; // Opsional, bisa null
            $new_barang->lokasi = $request->lokasi;
            $new_barang->status_barang = $request->status_barang;
            $new_barang->plate = $request->plate;
            $new_barang->save();

            return redirect('/kelola-barang')->with('success', 'Barang Added Successfully');
        } catch (\Exception $e) {
            return redirect('/add-barang')->with('fail', $e->getMessage());
        }
    }

    public function loadEditForm($id){
        $barang = barang::find($id);
        $jenis_barangs = jenis_barang::all();
        return view('kelola-barang.edit-barang',compact('barang', 'jenis_barangs'));
    }

    public function EditBarang(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'barang_id' => 'required|exists:barangs,id', // Pastikan barang_id valid
            'brand' => 'required|string',
            'nama_barang' => 'required|string',
            'no_catalog' => 'required|string',
            'jenis_barang_id' => 'required|exists:jenis_barangs,id', // Pastikan jenis_barang_id valid
            'stok' => 'required|numeric',
            'kadaluarsa' => 'nullable|date',
            'lokasi' => 'required|string',
            'status_barang' => 'nullable|string',
            'plate' => 'nullable|string',
        ]);

        try {
            // Update data barang berdasarkan id
            $update_barang = Barang::where('id', $request->barang_id)->update([
                'brand' => $request->brand,
                'nama_barang' => $request->nama_barang,
                'no_catalog' => $request->no_catalog,
                'jenis_barang_id' => $request->jenis_barang_id,
                'stok' => $request->stok,
                'kadaluarsa' => $request->kadaluarsa,
                'lokasi' => $request->lokasi,
                'status_barang' => $request->status_barang,
                'plate' => $request->plate,
            ]);

            return redirect('/kelola-barang')->with('success', 'Barang Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/edit-barang/' . $request->barang_id)->with('fail', $e->getMessage());
        }
    }

    public function deleteBarang($id){
        try {
            barang::where('id',$id)->delete();
            return redirect('kelola-barang')->with('success','Barang Deleted successfully!');
        } catch (\Exception $e) {
            return redirect('kelola-barang')->with('fail',$e->getMessage());
            
        }
    }

    //  Method to handle search
    public function search(Request $request)
    {
        $query = $request->input('query');
        $jenis_barangs = jenis_barang::all(); // Get all jenis_barang for dropdown

        // Split the search query into individual words
        $keywords = explode(' ', $query);

        // Use a query builder for searching
        $all_barangs = barang::with('jenisBarang') // Include relations
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where(function ($subQuery) use ($word) {
                        $subQuery->where('nama_barang', 'like', "%$word%")
                            ->orWhere('brand', 'like', "%$word%")
                            ->orWhere('no_catalog', 'like', "%$word%")
                            ->orWhere('stok', 'like', "%$word%")
                            ->orWhere('kadaluarsa', 'like', "%$word%")
                            ->orWhere('lokasi', 'like', "%$word%")
                            ->orWhere('status_barang', 'like', "%$word%")
                            ->orWhere('plate', 'like', "%$word%")
                            ->orWhereHas('jenisBarang', function ($relatedQuery) use ($word) {
                                $relatedQuery->where('nama_jenis_barang', 'like', "%$word%")
                                    ->orWhere('satuan_stok', 'like', "%$word%");
                            });
                    });
                }
            })
            ->paginate(25) // Add pagination
            ->appends(request()->except('page')); // Preserve query parameters

        return view('kelola-barang.index', compact('all_barangs', 'jenis_barangs'));
    }


    public function detailTransaksiBarang($id)
    {
        $barang = barang::findOrFail($id);

        $all_master_penerimaans = PenerimaanBarang::whereHas('detailpenerimaanbarang', function ($query) 
            use ($id) { $query->where('barang_id', $id); })->get();

        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_penerimaans = JenisPenerimaan::all();
        $all_detail_penerimaans = DetailPenerimaanBarang::where('barang_id', $id)->get();


        $all_master_pengeluarans = PengeluaranBarang::whereHas('detailpengeluaranbarang', function 
            ($query) use ($id) { $query->where('barang_id', $id); })->get();
        $all_jenis_pengeluarans = JenisPengeluaran::all();
        $all_detail_pengeluarans = DetailPengeluaranBarang::where('barang_id', $id)->get();

        return view('kelola-barang.detail', compact(
            'barang',
            'all_master_penerimaans', 
            'all_supkonpros', 
            'all_users', 
            'all_jenis_penerimaans', 
            'all_detail_penerimaans',

            'all_master_pengeluarans', 
            'all_jenis_pengeluarans',
            'all_detail_pengeluarans',
        ));
    }
}
