<?php

namespace App\Http\Controllers;

use App\Models\jenis_barang;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    public function loadAllJenisBarangs(){
        $all_jenis_barangs = jenis_barang::all();
        return view('kelola-jenis-barang.index',compact('all_jenis_barangs'));
    }

    public function loadAddJenisBarangForm(){
        return view('kelola-jenis-barang.add-jenis-barang');
    }

    public function AddJenisBarang(Request $request){
        // perform form validation here
        $request->validate([
            'nama_jenis_barang' => 'required|string',
            'satuan_stok' => 'required|string',
        ]);
        try {
             // register here
            $new_jenis_barang = new jenis_barang;
            $new_jenis_barang->nama_jenis_barang = $request->nama_jenis_barang;
            $new_jenis_barang->satuan_stok = $request->satuan_stok;
            $new_jenis_barang->save();

            return redirect('/kelola-jenis-barang')->with('success','Jenis Barang Added Successfully');
        } catch (\Exception $e) {
            return redirect('/add-jenis-barang')->with('fail',$e->getMessage());
        }
    }

    public function EditJenisBarang(Request $request){
        // perform form validation here
        $request->validate([
            'nama_jenis_barang' => 'required|string',
            'satuan_stok' => 'required|string',
        ]);
        try {
             // update  here
            $update_jenis_barang = jenis_barang::where('id',$request->jenis_barang_id)->update([
                'nama_jenis_barang' => $request->nama_jenis_barang,
                'satuan_stok' => $request->satuan_stok,
            ]);

            return redirect('/kelola-jenis-barang')->with('success','Jenis Barang Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/edit-jenis-barang')->with('fail',$e->getMessage());
        }
    }

    public function loadEditForm($id){
        $jenis_barang = jenis_barang::find($id);

        return view('kelola-jenis-barang.edit-jenis-barang',compact('jenis_barang'));
    }

    public function deleteJenisBarang($id){
        try {
            jenis_barang::where('id',$id)->delete();
            return redirect('kelola-jenis-barang')->with('success','Jenis Barang Deleted successfully!');
        } catch (\Exception $e) {
            return redirect('kelola-jenis-barang')->with('fail',$e->getMessage());
            
        }
    }

    //  Method to handle search
     public function search(Request $request)
     {
         $query = $request->input('query');
 
         // Cari berdasarkan nama, email, atau nomor telepon
         $all_jenis_barangs = jenis_barang::where('nama_jenis_barang', 'like', "%$query%")
             ->orWhere('satuan_stok', 'like', "%$query%")
             ->get();
 
         // Return view dengan hasil pencarian
         return view('kelola-jenis-barang.index', compact('all_jenis_barangs'));
     }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function show(jenis_barang $jenis_barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(jenis_barang $jenis_barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, jenis_barang $jenis_barang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(jenis_barang $jenis_barang)
    {
        //
    }
}
