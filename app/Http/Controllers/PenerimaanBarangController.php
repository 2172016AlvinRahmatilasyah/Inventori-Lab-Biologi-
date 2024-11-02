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

class PenerimaanBarangController extends Controller
{
    public function loadAllMasterPenerimaanBarang(){
        $all_master_penerimaans = PenerimaanBarang::all();
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_penerimaans = JenisPenerimaan::all();
        
        return view('barang-masuk.index',compact('all_master_penerimaans', 'all_supkonpros', 'all_users', 'all_jenis_penerimaans'));
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


    public function AddBarangMasuk(Request $request)
    {   
        $request->validate([
            'jenis_id' => 'required|exists:jenis_penerimaan_barangs,id',
            'supkonpro_id' => 'required|exists:supkonpros,id',
            'user_id' => 'required|exists:users,id',
            'nama_pengantar' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_diterima' => 'required|numeric',
            'harga' => 'required',  
            'total_harga' => 'required', 
        ]);
        
        // Clean up harga and total_harga to ensure they are valid numbers
        $harga = str_replace('.', '', $request->input('harga'));
        $total_harga = str_replace('.', '', $request->input('total_harga'));

        try {
            // Save the new PenerimaanBarang
            $new_penerimaan_barang = new PenerimaanBarang();
            $new_penerimaan_barang->jenis_id = $request->jenis_id;
            $new_penerimaan_barang->supkonpro_id = $request->supkonpro_id; 
            $new_penerimaan_barang->user_id = $request->user_id;
            $new_penerimaan_barang->nama_pengantar = $request->nama_pengantar; 
            $new_penerimaan_barang->keterangan = $request->keterangan;
            $new_penerimaan_barang->save();

            // Save the new DetailPenerimaanBarang
            $new_detail_penerimaan_barang = new DetailPenerimaanBarang();
            $new_detail_penerimaan_barang->master_penerimaan_barang_id = $new_penerimaan_barang->id;
            $new_detail_penerimaan_barang->barang_id = $request->barang_id;
            $new_detail_penerimaan_barang->jumlah_diterima = $request->jumlah_diterima;
            $new_detail_penerimaan_barang->harga = $harga; // Use cleaned harga
            $new_detail_penerimaan_barang->total_harga = $total_harga; // Use cleaned total_harga
            $new_detail_penerimaan_barang->save();

            return redirect('/master-barang-masuk/' . $request->jenis)->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            return redirect('/tambah-barang-masuk')->with('fail', $e->getMessage());
        }
    }


    public function deleteMasterBarang($id){
        try {
            PenerimaanBarang::where('id',$id)->delete();
            return redirect('/master-barang-masuk')->with('success','Deleted successfully!');
        } catch (\Exception $e) {
            return redirect('/master-barang-masuk')->with('fail',$e->getMessage());
            
        }
    }
    
}
