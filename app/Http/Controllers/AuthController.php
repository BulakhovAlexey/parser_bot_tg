<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.loginForm');
    }

    public function login_process(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($data)) {
            return redirect(route('chatIndex'));
        }

        return redirect(route('login'))->withErrors(['email' => 'Такого пользователя нет']);
    }

    public function logout()
    {
        auth()->logout();
        return redirect(route('main'));
    }
}
