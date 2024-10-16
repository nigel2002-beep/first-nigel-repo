<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function registerAdmin(){
        $admins =Admin::all();
        return view('auth.register_admin',compact('admins'));
    }
public function registerAdminPost(Request $request){
    $request->validate([
        'name'=> 'required',
        'address'=> 'required',
        'email'=> 'required',
        'contact'=> 'required',
        'password'=> 'required|min:8',
    ]);
    $admin = new Admin();
    $admin->name = $request->name;
    $admin->address = $request->address;
    $admin->email = $request->email;
    $admin->contact = $request->contact;
    $admin->password = Hash::make($request->input('password'));
    if ( $admin->save()){
        return redirect('/admin-dashboard')->with('success', 'Admin registered successfully');
    }
    return redirect(route("admin.register"))->with("error","Registration failed");
}
public function loginAdmin(){
    return view("auth.login_admin");
}
public function login_admin_post(Request $request) {
    $request->validate([
        'email' => "required|email",
        'password' => "required",
    ]);

    $credentials = [
        'email' => $request->input('email'),
        'password' => $request->input('password'),
    ];

    // Find the user by email
    $user = Admin::where('email', $credentials['email'])->first();

    // Check if user exists and the password is correct
    if ($user && Hash::check($credentials['password'], $user->password)) {
        Auth::login($user); // Log in the user
        return redirect('/admin-dashboard')->with('success', 'Admin logged in');
    }

    return redirect()->route("admin.login")->with("error", "Login failed");
}

}
