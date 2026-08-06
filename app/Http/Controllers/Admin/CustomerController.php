<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'user_type' => ['required', 'in:admin,seller,customer'],
        ]);

        $user->update([
            'user_type' => $validated['user_type'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Role updated for '.$user->name.'.');
    }
}
