<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function registerAgent(){
        $agents =User::all();
        return view('auth.register_agent',compact('agents'));
    }
public function registerAgentsPost(Request $request){
    $request->validate([
        'name'=> 'required',
        'email'=> 'required',
        'address'=> 'required',
        'city'=> 'required',
        'contact_number'=> 'required',
        'owner_first_name'=> 'required',
        'owner_last_name'=> 'required',
        'owner_email'=> 'required',
        'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'email_verified_at'=> 'required',
        'password'=> 'required',
    ]);
    $agent = new User();
    $agent->name = $request->name;
    $agent->email = $request->email;
    $agent->address = $request->address;
    $agent->city = $request->city;
    $agent->contact_number = $request->contact_number;
    $agent->owner_first_name = $request->owner_first_name;
    $agent->owner_last_name = $request->owner_last_name;
    $agent->owner_email = $request->owner_email;
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public'); // Store logo in 'storage/app/public/logos'

        // Save the logo path to the agent's record
        $agent->logo = $logoPath;
    }
    $agent->email_verified_at =  $request->email_verified_at;
    $agent->password = $request->password;
    if ($agent->save()){
        return redirect('/welcome')->with('success', 'Agent inserted successfully');
    }
    return redirect(route("agent.register"))->with("error","Registration failed");
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
            return redirect('/welcome')->with('success','Agent logged in');
        }
        return redirect(route("agent.login"))->with("error","Login failed");
}
}
