<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar user.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $currentUserRole = auth()->user()->role;

        $allowedRoles = $currentUserRole === 'super_admin'
            ? 'super_admin,admin,kasir'
            : 'kasir';

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => "required|in:$allowedRoles",
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'active', // Default aktif
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUserRole = auth()->user()->role;

        if ($user->role === 'super_admin' && auth()->id() !== $user->id) {
            return back()->with('error', 'Tidak dapat mengubah Super Admin.');
        }

        $allowedRoles = $currentUserRole === 'super_admin'
            ? 'super_admin,admin,kasir'
            : 'kasir';

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => "required|in:$allowedRoles",
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Nonaktifkan user.
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Anda sendiri.');
        }

        if ($user->role === 'super_admin') {
            return back()->with('error', 'Tidak dapat menonaktifkan Super Admin.');
        }

        $user->update(['status' => 'inactive']);

        return back()->with('success', 'User berhasil dinonaktifkan.');
    }

    /**
     * Aktifkan user.
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);

        $user->update(['status' => 'active']);

        return back()->with('success', 'User berhasil diaktifkan kembali.');
    }

    /**
     * Menghapus user sepenuhnya.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role === 'super_admin') {
            return back()->with('error', 'Tidak dapat menghapus Super Admin.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
