<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'password' => 'nullable|min:6|confirmed',
            'email' => 'nullable|email|unique:users,email',
        ]);

        // ONLY UPDATE NAME + BIRTHDAY
        $user->name = $request->name;
        $user->birthday = $request->birthday;
        $user->email =$request->email;

        // PASSWORD UPDATE (ONLY IF FILLED)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }



        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
