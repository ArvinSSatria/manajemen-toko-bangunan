<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected UserRepository $repository)
    {
    }

    public function index(Request $request)
    {
        $users = $this->repository->paginate(15, $request->only('search', 'store_id', 'role'));
        $stores = Store::where('is_active', true)->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'stores', 'roles'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'store_id' => 'nullable|exists:stores,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'store_id' => $data['store_id'],
            'email_verified_at' => now(),
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }


    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'store_id' => 'nullable|exists:stores,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'store_id' => $data['store_id'],
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => bcrypt($data['password'])]);
        }

        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
