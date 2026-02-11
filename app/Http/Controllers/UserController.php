<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['staff', 'super_admin'])],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['staff', 'super_admin'])],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        // Prevent locking yourself out
        if ($request->user()->id === $user->id && $data['role'] !== 'super_admin') {
            return back()->withErrors(['role' => 'You cannot remove your own Super Admin role.'])->withInput();
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated!');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        // Prevent deleting last super_admin
        if ($user->role === 'super_admin') {
            $superCount = User::where('role', 'super_admin')->count();
            if ($superCount <= 1) {
                return back()->withErrors(['delete' => 'You cannot delete the last Super Admin.']);
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted!');
    }
}
