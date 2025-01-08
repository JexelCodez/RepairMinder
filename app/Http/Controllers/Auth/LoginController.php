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
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'siswa') {
                return redirect('/');
            }
            if ($user->role === 'guru') {
                return redirect()->route('home.guru');
            }
            if ($user->role === 'teknisi') {
                return redirect('home');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
