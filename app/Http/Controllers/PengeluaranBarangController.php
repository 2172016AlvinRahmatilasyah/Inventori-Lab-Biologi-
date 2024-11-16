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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Testing\Fakes\PendingMailFake;
use Illuminate\Support\Facades\DB; 


class PengeluaranBarangController extends Controller
{
    public function loadAllMasterPengeluaranBarang(){
        $all_master_pengeluarans = PengeluaranBarang::all();
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_pengeluarans = JenisPengeluaran::all();
        
        return view('barang-keluar.index',compact('all_master_pengeluarans', 'all_supkonpros', 'all_users', 
                    'all_jenis_pengeluarans'));
    }
    
    public function MasterBarangKeluarSearch(Request $request)
    {
        $query = $request->input('query');

        $all_master_pengeluarans = PengeluaranBarang::whereHas('supkonpro', function ($q) use ($query) {
                $q->where('nama', 'like', "%$query%");
            })
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->orWhereHas('jenisPengeluaranBarang', function ($q) use ($query) {
                $q->where('jenis', 'like', "%$query%");
            })
            ->orWhere('nama_pengambil', 'like', "%$query%")
            ->orWhere('keterangan', 'like', "%$query%")
            ->get();

        return view('barang-keluar.index', compact('all_master_pengeluarans'));
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


    public function AddBarangKeluar(Request $request)
    {   
        $request->validate([
            'jenis_id' => 'required|exists:jenis_pengeluaran_barangs,id',
            'supkonpro_id' => 'required|exists:supkonpros,id',
            'user_id' => 'required|exists:users,id',
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
            $new_pengeluaran_barang = new PengeluaranBarang();
            $new_pengeluaran_barang->jenis_id = $request->jenis_id;
            $new_pengeluaran_barang->supkonpro_id = $request->supkonpro_id; 
            $new_pengeluaran_barang->user_id = $request->user_id;
            $new_pengeluaran_barang->nama_pengambil = $request->nama_pengambil; 
            $new_pengeluaran_barang->keterangan = $request->keterangan;
            $new_pengeluaran_barang->save();

           
            $new_detail_pengeluaran_barang = new DetailPengeluaranBarang();
            $new_detail_pengeluaran_barang->master_pengeluaran_barang_id = $new_pengeluaran_barang->id;
            $new_detail_pengeluaran_barang->barang_id = $request->barang_id;
            $new_detail_pengeluaran_barang->jumlah_keluar = $request->jumlah_keluar;
            $new_detail_pengeluaran_barang->harga = $harga; 
            $new_detail_pengeluaran_barang->total_harga = $total_harga; 
            $new_detail_pengeluaran_barang->save();

           
            $barang = Barang::findOrFail($request->barang_id); 
            $barang->stok -= $request->jumlah_keluar; 
            $barang->save(); 

            return redirect('/master-barang-keluar/' . $request->jenis)->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            return redirect('/tambah-barang-keluar')->with('fail', $e->getMessage());
        }
    }

    public function deletePengeluaranBarang($id)
    {
        try {
            // Start a transaction to ensure data consistency
            DB::beginTransaction();
            
            // Retrieve all associated detail records
            $details = DetailPengeluaranBarang::where('master_pengeluaran_barang_id', $id)->get();

            // Iterate over each detail to adjust the stock
            foreach ($details as $detail) {
                $barang = Barang::find($detail->barang_id);

                if ($barang) {
                    // Increase the stock back based on the jumlah_keluar
                    $barang->stok += $detail->jumlah_keluar;
                    $barang->save();
                }
            }

            // Delete the detail records associated with this master record
            DetailPengeluaranBarang::where('master_pengeluaran_barang_id', $id)->delete();

            // Delete the master PengeluaranBarang record
            PengeluaranBarang::where('id', $id)->delete();

            // Commit the transaction
            DB::commit();
            
            return redirect('/master-barang-keluar')->with('success', 'Deleted successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            return redirect('/master-barang-keluar')->with('fail', $e->getMessage());
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
        $masterPengeluaran = PengeluaranBarang::with('detailpengeluaranbarang')->findOrFail($id);
        $all_supkonpros = supkonpro::all();
        $all_users = User::all();
        $all_jenis_pengeluarans = JenisPengeluaran::all();
        $user = Auth::user(); 
        $all_barangs = barang::all();
        $detail_pengeluaran = DetailPengeluaranBarang::all();
        
        return view('barang-keluar.edit-barang-keluar', compact(
            'masterPengeluaran', 'all_supkonpros', 'all_users', 'all_jenis_pengeluarans', 'user',
            'all_barangs', 'detail_pengeluaran'
        ));
    }

    public function EditPengeluaranBarang(Request $request)
    {   
        $request->validate([
            'masterPengeluaran_id' => 'required|exists:master_pengeluaran_barangs,id',
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
            $detailPengeluaranBarang = DetailPengeluaranBarang::where('master_pengeluaran_barang_id', $request->masterPengeluaran_id)
                ->where('barang_id', $request->barang_id)
                ->firstOrFail();

            // Hitung selisih jumlah keluar
            $selisihJumlah = $request->jumlah_keluar - $detailPengeluaranBarang->jumlah_keluar;

            // Update pengeluaran master
            PengeluaranBarang::where('id', $request->masterPengeluaran_id)->update([
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

            return redirect('/master-barang-keluar/')->with('success', 'Edit Successfully');
        } catch (\Exception $e) {
            return redirect('/edit-pengeluaran-barang/' . $request->masterPengeluaran_id)->with('fail', $e->getMessage());
        }
    }

}
