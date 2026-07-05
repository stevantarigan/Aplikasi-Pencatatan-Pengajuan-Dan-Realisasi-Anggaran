<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Staff tidak boleh masuk halaman daftar user sama sekali
        if (auth()->user()->role == 'staff') {
            abort(403, 'Unauthorized access. Halaman ini hanya untuk Admin dan Kepala Gizi.');
        }

        // Query dasar
        $query = User::query();

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Urutkan dan paginate
        $users = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.daftarUser', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Admin dan Kepala Gizi bisa create
     */
    public function create()
    {
        // Cek role - staff tidak boleh akses
        if (auth()->user()->role == 'staff') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     * Admin dan Kepala Gizi bisa store
     */
    public function store(Request $request)
    {
        // Cek role - staff tidak boleh akses
        if (auth()->user()->role == 'staff') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,keuangan,kepala_gizi,staff',
        ]);

        try {
            User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'active',
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * Admin dan Kepala Gizi bisa lihat detail
     */
    public function show(User $user)
    {
        // Cek role - staff tidak boleh akses
        if (auth()->user()->role == 'staff') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     * Admin dan Kepala Gizi bisa edit
     */
    public function edit(User $user)
    {
        // Cek role - staff tidak boleh akses
        if (auth()->user()->role == 'staff') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * Admin dan Kepala Gizi bisa update
     */
    public function update(Request $request, User $user)
    {
        // Cek role - staff tidak boleh akses
        if (auth()->user()->role == 'staff') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,keuangan,kepala_gizi,staff',
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * HANYA ADMIN yang bisa delete
     */
    public function destroy(User $user)
    {
        // Hanya admin yang boleh hapus
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Hanya Admin yang dapat menghapus user.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Update user status (active/inactive)
     * Hanya Admin yang bisa update status
     */
    public function updateStatus(Request $request, User $user)
    {
        // Hanya admin yang boleh update status
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya Admin yang dapat mengubah status user.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah status akun Anda sendiri.'
            ], 403);
        }

        $user->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diperbarui.',
            'status' => $user->status
        ]);
    }
}