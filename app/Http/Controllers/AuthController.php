<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthVerifyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;         
use App\Models\User;               
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{
    public function index()
    {
        return view('HOMEPAGES.FITUR.login');
    }

    public function Registrasi(){
        return view('HOMEPAGES.FITUR.registrasi');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'CLIENT', 
        ]);

        auth()->login($user);

        // arahkan ke dashboard client atau ke form login
        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }

 public function verify(UserAuthVerifyRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard'); 
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
