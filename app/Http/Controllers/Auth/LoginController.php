<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Session;

use App\Models\User;

use Hash;

class LoginController extends Controller
{
    public function postRegistration(Request $request)
    {  
        $request->validate([

            'name' => 'required',

            'email' => 'required|email|unique:users',

            'password' => 'required|min:6',

        ]);

        $data = $request->all();

        $check = $this->create($data);
        return redirect('dashboard')->withSuccess('Great! You have Successfully loggedin');

    }
    public function create(array $data)
    {
      return User::create([

        'name' => $data['name'],

        'email' => $data['email'],

        'password' => Hash::make($data['password'])

      ]);

    }
    public function postLogin(Request $request)
    {

        $request->validate(['email' => 'required','password' => 'required',]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            return redirect()->intended('dashboard')->withSuccess('You have Successfully loggedin');

        }
        return redirect("/")->withSuccess('Oppes! You have entered invalid credentials');

    }
    public function logout() 
    {
        Session::flush();
        Auth::logout();
        return Redirect('/');

    }
    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $exists = User::where('email', $email)->exists();
        if ($exists) {
            return response()->json(['status' => true, 'message' => 'valide email']);
        } else {
            return response()->json(['status' => false, 'message' => 'Email not found']);
        }
    }
    public function checkPassword(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Email not found']);
        }

        if (Hash::check($password, $user->password)) {
            return response()->json(['status' => true, 'message' => 'Password correct']);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid password']);
        }
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function Dashboard()
    {
        return view('layouts.app');
    }
    public function registration()
    {
        return view('auth.registration');

    }
}
