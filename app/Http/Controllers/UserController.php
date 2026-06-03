<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // SEARCH LOGIC
        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }


    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,seller,customer',
        ]);

        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }
        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Role updated successfully');
    }
}
