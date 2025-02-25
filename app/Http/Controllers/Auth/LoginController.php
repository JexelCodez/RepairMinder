<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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
            if ($user->role === 'guru') {
                Alert::success('Mantap', 'Anda berhasil login!');
                return redirect('/');
            }
            // if ($user->role === 'guru') {
            //     return redirect()->route('home.guru');
            // }
            if ($user->role === 'admin') {
                return redirect('/sija');
            }
            else {
                return redirect()->route('home');
            }

            // if ($user->role === 'teknisi') {
            //     $zone = Str::lower(optional($user->zoneUser)->zone_name);
            //     if (in_array($zone, ['sija', 'dkv', 'sarpras'])) {
            //         return redirect("/{$zone}");
            //     }
            // }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
