<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/register-agent',[AgentController::class,'registerAgent'])->name('agent.register');
Route::post('/regiser-agent',[AgentController::class,'registerAgentsPost'])->name('agent.register.post');

Route::get('/login-agent',[AgentController::class,'loginAgent'])->name('agent.login');
Route::post('/login-agent',[AgentController::class,'loginAgentPost'])->name('agent.login.post');

Route::get('/register-admin',[AdminController::class,'registerAdmin'])->name('admin.register');
Route::post('/regiser-admin',[AdminController::class,'registerAdminPost'])->name('admin.register.post');

 Route::get('/login-admin',[AdminController::class,'loginAdmin'])->name('admin.login');
 Route::post('/login-admin',[AdminController::class,'login_admin_post'])->name('admin.login.post');

 Route::get('/admin-dashboard',[AdminController::class,'adminDashboard'])->middleware('admin');
//  public function adminDashboard(){
//     return view('layouts.admin_base');
//     }
