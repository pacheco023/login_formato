<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;

use Session;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view( 'auth.login');
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
            ->widthSuccess('Bienvenido');
        }

        return redirect("login")->withSuccess('Las credenciales son incorrectas');

    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $user = $this->create($data);

        Auth::login($user);
        return redirect("dashboard")->withSuccess('Te has registrado satisfactoriamente!');
    }

    public function create(array $data)
    {
        return user::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function dashboard()
    {
        if(Auth::check()){

            return view( 'dashboard');
        }

        return redirect( "login")->widthSuccess('No tienes acceso a esta seccion');
    }

    public function signOut() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
