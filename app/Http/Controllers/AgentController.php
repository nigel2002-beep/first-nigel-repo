<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index(){

        return view('agent_store');
    }
    public function registerAgent(){
        $agents =User::all();
        return view('auth.register_agent',compact('agents'));
    }
    public function registerAgentsPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'address' => 'required',
            'is_admin' => 'required',
            'city' => 'required',
            'contact_number' => 'required',
            'owner_first_name' => 'required',
            'owner_last_name' => 'required',
            'owner_email' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'email_verified_at' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $agent = new User();
        $agent->name = $request->name;
        $agent->email = $request->email;
        $agent->address = $request->address;
        $agent->is_admin = $request->is_admin;
        $agent->city = $request->city;
        $agent->contact_number = $request->contact_number;
        $agent->owner_first_name = $request->owner_first_name;
        $agent->owner_last_name = $request->owner_last_name;
        $agent->owner_email = $request->owner_email;

        $agent->assignRole('user');

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $agent->logo = $logoPath;
        }

        $agent->email_verified_at = $request->email_verified_at;

        $agent->password = Hash::make($request->password);

        if ($agent->save()) {

            Auth::login($agent);
        //    $agent= Auth::user();

            return redirect('/user')->with('success', 'Agent registered and logged in successfully');
        }

        return redirect(route("agent.register"))->with("error", "Registration failed");
    }

public function loginAgent(){
    return view("auth.login_agent");
}
public function loginAgentPost(Request $request){
    $request->validate([
        'owner_email'=> "required",
        'password' => "required",
    ]);
        $credentials= $request->only("owner_email","password");
        if(Auth::attempt($credentials)){

            return redirect('/user')->with('success','Agent logged in');
        }
        else {

            return back()->withErrors(['email' => 'These credentials do not match our records.']);
        }
}
public function logoutAgent(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login-agent')->with('success', 'Agent Successfully logged out');
}

// public function agentDashboard(){
//     return view('agent_store');
// }
public function items()
{
    // $productCount = Product::count();
    $agent = User::where('is_admin', 1)->count();
    // $category = Category::count();
    return view('layouts.admin_base',compact('productCount','agent','category'));

}
public function fields()
{
    $productCount = Product::count();
    $category = Category::count();
    return view('layouts.agent_store_base',compact('productCount','agent','category'));

}
}
