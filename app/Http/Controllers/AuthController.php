<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!$request->has('email') || !$request->has('password')) {
            return response('Email e senha são obrigatórios', 422);
        }

        if ($this->hasSession()) {
            return response(true, 200);
        }

        if (!$this->autenticar($request->email, $request->password)) {
            return response('Email ou senha inválidos', 403);
        }

        return response(true, 200);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    private function autenticar($email, $password)
    {
        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ]);
    }

    private function hasSession()
    {
        return Auth::check();
    }
}
