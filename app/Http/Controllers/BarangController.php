<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\jenis_barang;
use Illuminate\Http\Request;

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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, barang $barang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(barang $barang)
    {
        //
    }
}
