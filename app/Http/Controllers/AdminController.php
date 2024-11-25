<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(){
        return view('welcome');

    }
    public function registerAdmin(){
        $admins =User::all();
        return view('auth.register_admin',compact('admins'));
    }
// Controller method for handling admin registration (POST)
public function registerAdminPost(Request $request)
{
    // Validate incoming data
    $request->validate([
        'name' => 'required',
        'address' => 'required',
        'email' => 'required|email|unique:users,email', // Ensure email is unique
        'contact' => 'required',
        'password' => 'required|min:8|confirmed', // Add password confirmation
    ]);

    // Create the new admin user
    $admin = new User();
    $admin->name = $request->name;
    $admin->address = $request->address;
    $admin->email = $request->email;
    $admin->contact = $request->contact;
    $admin->password = Hash::make($request->password);


    if ($admin->save()) {

        $admin->assignRole('admin');
        Auth::login($admin);
        return redirect()->route('admin.dashboard')->with('success', 'Admin registered and logged in successfully');
    }

    return redirect()->route('admin.register')->with('error', 'Registration failed');
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
    $admin = User::where('email', $credentials['email'])->first();
    if (Auth::attempt($credentials)) {
        return redirect('/admin')->with('success', 'Admin logged in');
    } else {

        return back()->withErrors(['email' => 'These credentials do not match our records.']);
    }
}
public function logoutAdmin(Request $request)
{
    Auth::logout(); // Log the user out

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login-admin')->with('success', 'Admin Successfully logged out');
}
public function categoriesTable(){
    $categories = Category::all();
    return view('admin.list_categories',compact('categories'));

}
public function products_table(){
    $products = Product::all();
    return view('admin.list_products',compact('products'));
}
public function agents_table(){
    $agents = User::where('is_admin', 1)->get();
    return view('admin.list_agents',compact('agents'));
}
public function items()
{
    $productCount = Product::count();
    $agent = User::where('is_admin', 1)->count();
    $category = Category::count();
    return view('layouts.admin_base',compact('productCount','agent','category'));

}
}
