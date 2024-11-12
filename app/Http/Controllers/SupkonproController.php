<?php

namespace App\Http\Controllers;

use App\Models\supkonpro;
use Illuminate\Http\Request;

class SupkonproController extends Controller
{

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

    public function loadAddSupkonproForm($jenis){
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

    public function loadEditForm($id, $jenis){
        $supkonpros = supkonpro::findOrFail($id);

        return view('supkonpro.edit-supkonpro', compact('supkonpros', 'jenis'));

    }
    
    public function EditSupkonpro(Request $request, $id, $jenis)
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

            return redirect('/supkonpro/' . $jenis)->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/supkonpro/' . $jenis)->with('fail', $e->getMessage());
        }
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
}
