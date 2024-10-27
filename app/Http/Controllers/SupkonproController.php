<?php

namespace App\Http\Controllers;

use App\Models\supkonpro;
use Illuminate\Http\Request;

class SupkonproController extends Controller
{
    // public function handleType($jenis)
    // {
    //     // Check the type and return the appropriate view or data
    //     if ($jenis === 'supplier') {
    //         $jenis = 'Supplier';
    //     } elseif ($jenis === 'konsumen') {
    //         $jenis = 'Konsumen';
    //     } elseif ($jenis === 'proyek') {
    //         $jenis = 'Proyek';
    //     } else {
    //         // Handle invalid type if necessary
    //         abort(404);
    //     }

    //     // Return a view with the corresponding 'jenis'
    //     return view('kelola-supkonpro.index', compact('jenis'));
    // }
    protected $jenis;

    public function loadAllSupkonpros($jenis)
    {
        if ($jenis === 'supplier') {
            $jenis = 'Supplier';
        } elseif ($jenis === 'konsumen') {
            $jenis = 'Konsumen';
        } elseif ($jenis === 'proyek') {
            $jenis = 'Proyek';
        } else {
            // Handle invalid type if necessary
            abort(404);
        }
        // Fetch the relevant data based on the 'jenis' passed (Supplier, Konsumen, or Proyek)
        $all_supkonpros = supkonpro::where('jenis', $jenis)->get();

        // Pass the data and jenis to the view
        return view('supkonpro.index', compact('all_supkonpros', 'jenis'));
    }

    public function loadAddForm($jenis){
        $all_supkonpros = supkonpro::where('jenis', $jenis)->get();;
        return view('supkonpro.add-supkonpro', compact('all_supkonpros', 'jenis'));
    }

    public function AddSupkonpro(Request $request){
        // perform form validation here
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'jenis' => ['required', 'in:supplier,konsumen,proyek'],
        ]);

        try {
            $new_supkonpro = new supkonpro();
            $new_supkonpro->nama = $request->nama;
            $new_supkonpro->alamat = $request->alamat; 
            $new_supkonpro->kota = $request->kota;
            $new_supkonpro->telepon = $request->telepon; 
            $new_supkonpro->email = $request->email;
            $new_supkonpro->jenis = $request->jenis;
            $new_supkonpro->save();

            return redirect('/supkonpro/' . $request->jenis)->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            return redirect('/add-supkonpro')->with('fail', $e->getMessage());
        }
    }

    public function EditSupkonpro(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email',
            'jenis' => ['required', 'in:supplier,konsumen,proyek'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        try {
            $update_supkonpro = supkonpro::where('id', $request->supkonpro_id)->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'jenis' => $request->jenis,
                'status' => $request->status,

            ]);

            return redirect('/supkonpro')->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/supkonpro/' . $request->supkonpro_id)->with('fail', $e->getMessage());
        }
    }

    public function loadEditForm($id){
        $supkonpros = supkonpro::find($id);
        return view('supkonpro.edit-supkonpro',compact('supkonpros'));
    }

    public function deleteSupkonpro($id, $jenis)
    {
        try {
            // Cari item berdasarkan ID
            $supkonpro = supkonpro::where('id', $id)->where('jenis', ucfirst($jenis))->firstOrFail();

            // Hapus item
            $supkonpro->delete();

            // Redirect ke halaman utama jenis
            return redirect()->route('supkonpro', ['jenis' => strtolower($jenis)])->with('success', 'Data Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('supkonpro', ['jenis' => strtolower($jenis)])->with('fail', 'Data not found or already deleted.');
        }
    }



    public function search(Request $request, $jenis)
    {
        $query = $request->input('query');

        // Tambahkan kondisi untuk jenis
        $all_supkonpros = supkonpro::where('jenis', ucfirst($jenis))
            ->where(function ($q) use ($query) {
                $q->where('nama', 'like', "%$query%")
                ->orWhere('alamat', 'like', "%$query%")
                ->orWhere('kota', 'like', "%$query%")
                ->orWhere('telepon', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->orWhere('status', 'like', "%$query%");
            })
            ->get();

        return view('supkonpro.index', compact('all_supkonpros', 'jenis'));
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
    public function show(supkonpro $supkonpro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(supkonpro $supkonpro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, supkonpro $supkonpro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(supkonpro $supkonpro)
    {
        //
    }
}
