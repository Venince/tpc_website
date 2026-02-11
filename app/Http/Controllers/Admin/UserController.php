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
        // ✅ Users page contains Admin/Staff ONLY
        // Super Admin is NOT listed here
        $users = User::where('is_admin', true)
            ->where('is_super_admin', false)
            ->orderByDesc('created_at')
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
            'name' => ['required','string','max:255'],
            'email' => ['required','string','lowercase','email','max:255', Rule::unique('users','email')],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        // ✅ Always create as Admin/Staff
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => true,
            'is_super_admin' => false,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin/Staff account created.');
    }

    public function edit(User $user)
    {
        // ✅ Super Admin should NOT be edited here (use /profile instead)
        abort_if($user->is_super_admin, 404);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // ✅ Super Admin should NOT be edited here (use /profile instead)
        abort_if($user->is_super_admin, 404);

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','lowercase','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:8','confirmed'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // ✅ Keep role fixed as Admin/Staff
        $user->is_admin = true;
        $user->is_super_admin = false;

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Admin/Staff updated.');
    }

    public function destroy(User $user)
    {
        abort_if($user->is_super_admin, 404);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Admin/Staff deleted.');
    }
}
