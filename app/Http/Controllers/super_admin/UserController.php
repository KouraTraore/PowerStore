<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('super_admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super_admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email'    => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,super_admin',
            'nom'      => 'nullable|string|max:50',
            'prenom'   => 'nullable|string|max:50',
        ]);

        User::create($validated);

        return redirect()->route('admin.super.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('super_admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
           return view('super_admin.users.edit', compact('user'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            'email'    => 'required|email|max:100|unique:users,email,'.$user->id,
            'role'     => 'required|in:admin,super_admin',
            'nom'      => 'nullable|string|max:50',
            'prenom'   => 'nullable|string|max:50',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = $request->password; // le mutator s'occupe du hash
        }

        $user->update($validated);

        return redirect()->route('admin.super.users.index')
            ->with('success', 'Utilisateur modifié.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
          if ($user->id === Auth::user()->id) {
            return back()->withErrors(['Vous ne pouvez pas supprimer votre propre compte.']);
    }
    if ($user->role === 'super_admin' && User::where('role', 'super_admin')->count() <= 1) {
            return back()->withErrors(['Impossible de supprimer le dernier Super Admin.']);
        }

        $user->delete();

        return redirect()->route('admin.super.users.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === Auth::user()->id) {
            return back()->withErrors(['Vous ne pouvez pas modifier votre propre statut.']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Statut modifié.');
    }
}
