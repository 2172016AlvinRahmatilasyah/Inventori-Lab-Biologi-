<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $role;

    public function loadAllUsers($role)
    {
        if ($role === 'admin') {
            $role = 'admin';
        } elseif ($role === 'user') {
            $role = 'user';
        } else {
            abort(404);
        }
        $all_users = User::where('role', $role)->get();

        return view('kelola-user.index', compact('all_users', 'role'));
    }

    public function loadAddUserForm($role){
        $all_users = User::where('role', $role)->get();;
        return view('kelola-user.add-user', compact('all_users', 'role'));
    }

    public function AddUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'role' => ['required', 'in:admin,user'],
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'password' => 'required|min:8|confirmed', // 'confirmed' rule added
        ]);

        try {
            $new_user = new User();
            $new_user->name = $request->name;
            $new_user->role = $request->role;
            $new_user->email = $request->email;
            $new_user->phone_number = $request->phone_number;
            $new_user->password = bcrypt($request->password); // Hash password before saving
            $new_user->save();

            return redirect('/kelola-user/' . $request->role)->with('success', 'Data Added Successfully');
        } catch (\Exception $e) {
            return redirect('/kelola-user')->with('fail', $e->getMessage());
        }
    }


    public function loadEditForm($id, $role){
            $users = User::findOrFail($id);

            return view('kelola-user.edit-user', compact('users', 'role'));

    }

    public function EditUser(Request $request, $id, $role)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'phone_number' => 'required|string',
        'current_password' => 'required', // Add validation for current password
        'password' => 'nullable|min:8',
    ]);

    try {
        $user = User::findOrFail($id);

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect('/kelola-user/' . $role)
                   ->with('fail', 'Current password is incorrect.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        // Update password if there's a new input
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/kelola-user/' . $role)->with('success', 'Updated Successfully');
    } catch (\Exception $e) {
        return redirect('/kelola-user/' . $role)->with('fail', $e->getMessage());
    }
}


    public function deleteUser($id, $role)
    {
        try {
            // Cari item berdasarkan ID
            $user = User::where('id', $id)->where('role', ucfirst($role))->firstOrFail();

            // Hapus item
            $user->delete();

            // Redirect ke halaman utama jenis
            return redirect()->route('user', ['role' => strtolower($role)])->with('success', 'Data Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->route('user', ['jenis' => strtolower($role)])->with('fail', 'Data not found or already deleted.');
        }
    }

    public function search(Request $request, $role)
    {
        // Get the search query from the request
        $query = $request->input('query');

        // Split the query into individual keywords for multi-word search
        $keywords = explode(' ', $query);

        $all_users = User::where('role', ucfirst($role))
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where(function ($subQuery) use ($word) {
                        $subQuery->where('name', 'like', "%$word%")
                            ->orWhere('email', 'like', "%$word%")
                            ->orWhere('phone_number', 'like', "%$word%");
                    });
                }
            })
            ->get();
        return view('kelola-user.index', compact('all_users', 'role'));
    }

}
