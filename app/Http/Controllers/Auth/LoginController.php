<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('user_view.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');  // Redirect setelah berhasil login
        }

        // return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
        // If login fails, return with error
        dd('Error: The provided credentials are incorrect.');
    }
}
