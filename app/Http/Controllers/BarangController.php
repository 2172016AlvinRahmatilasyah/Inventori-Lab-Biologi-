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
    
    public function loadAllBarangs(){
        $all_barangs = barang::all();
        return view('kelola-barang.index',compact('all_barangs'));
    }

    public function loadAddBarangForm(){
        $jenis_barangs = jenis_barang::all();
        return view('kelola-barang.add-barang', compact('jenis_barangs'));
    }

    public function AddBarang(Request $request){
        // perform form validation here
        $request->validate([
            'nama_barang' => 'required|string',
            'jenis_barang_id' => 'required|exists:jenis_barangs,id', // Pastikan jenis_barang_id valid
            'stok' => 'required|numeric',
            'kadaluarsa' => 'nullable|date',
            'lokasi' => 'required|string',
        ]);

        try {
            // Buat objek baru untuk barang
            $new_barang = new Barang;
            $new_barang->nama_barang = $request->nama_barang;
            $new_barang->jenis_barang_id = $request->jenis_barang_id; // Ambil dari input
            $new_barang->stok = $request->stok;
            $new_barang->kadaluarsa = $request->kadaluarsa; // Opsional, bisa null
            $new_barang->lokasi = $request->lokasi;
            $new_barang->save();

            return redirect('/kelola-barang')->with('success', 'Barang Added Successfully');
        } catch (\Exception $e) {
            return redirect('/add-barang')->with('fail', $e->getMessage());
        }
    }

    public function EditBarang(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'barang_id' => 'required|exists:barangs,id', // Pastikan barang_id valid
            'nama_barang' => 'nullable|string',
            'jenis_barang_id' => 'nullable|exists:jenis_barangs,id', // Pastikan jenis_barang_id valid
            'stok' => 'nullable|numeric',
            'kadaluarsa' => 'nullable|date',
            'lokasi' => 'nullable|string',
        ]);

        try {
            // Update data barang berdasarkan id
            $update_barang = Barang::where('id', $request->barang_id)->update([
                'nama_barang' => $request->nama_barang,
                'jenis_barang_id' => $request->jenis_barang_id,
                'stok' => $request->stok,
                'kadaluarsa' => $request->kadaluarsa,
                'lokasi' => $request->lokasi,
            ]);

            return redirect('/kelola-barang')->with('success', 'Barang Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/edit-barang/' . $request->barang_id)->with('fail', $e->getMessage());
        }
    }



    public function loadEditForm($id){
        $barang = barang::find($id);
        $jenis_barangs = jenis_barang::all();
        return view('kelola-barang.edit-barang',compact('barang', 'jenis_barangs'));
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
 
         // Cari berdasarkan nama, email, atau nomor telepon
         $all_barangs = barang::where('nama_barang', 'like', "%$query%")
             ->orWhere('jenis_barang_id', 'like', "%$query%")
             ->orWhere('stok', 'like', "%$query%")
             ->orWhere('kadaluarsa', 'like', "%$query%")
             ->orWhere('lokasi', 'like', "%$query%")
             ->get();
 
         // Return view dengan hasil pencarian
         return view('kelola-barang.index', compact('all_barangs'));
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
