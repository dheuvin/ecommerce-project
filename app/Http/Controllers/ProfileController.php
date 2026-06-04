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
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // ONLY UPDATE NAME + BIRTHDAY
        $user->name = $request->name;
        $user->birthday = $request->birthday;

        // PASSWORD UPDATE (ONLY IF FILLED)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // EMAIL IS NOT ALLOWED TO CHANGE ❌

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
