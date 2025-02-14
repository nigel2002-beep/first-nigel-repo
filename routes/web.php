<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin')->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});
// Route::get('/admin', [AdminController::class, 'index'])->middleware('auth', 'permission:create role','admin')->name('admin.dashboard');
// Route::get('/user', [AgentController::class, 'index'])->middleware('auth','verified','agent')->name('user.dashboard');
Route::middleware('agent')->group(function(){
    Route::get('/user', [AgentController::class, 'index'])->name('user.dashboard');
    Route::get('/list-products',[ProductController::class,'products_table'])->name('products.list');
    Route::get('edit-products/{id}', [ProductController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'updateProductPost'])->name('products.update');
    Route::delete('/product-delete/{id}',[ProductController::class,'deleteProduct'])->name('product.delete');
    Route::get('/insert-products',[ProductController::class,'displayProducts'])->name('add.product');
    Route::post('/insert-products',[ProductController::class,'addProductPost'])->name('add.product.post');


});
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function(){
Route::resource('permissions', PermissionController::class);
Route::get('permissions/{permissionId}/delete', [PermissionController::class,'destroy']);

Route::resource('roles', RoleController::class);
Route::get('roles/{roleId}/delete', [RoleController::class,'destroy']);
Route::get('roles/{roleId}/give-permissions',[RoleController::class,'addPermissionToRole']);
Route::put('roles/{roleId}/give-permissions',[RoleController::class,'givePermissionToRole']);
// });

Route::get('/register-agent',[AgentController::class,'registerAgent'])->name('agent.register');
Route::post('/regiser-agent',[AgentController::class,'registerAgentsPost'])->name('agent.register.post');

Route::get('/login-agent',[AgentController::class,'loginAgent'])->name('agent.login');
Route::post('/login-agent',[AgentController::class,'loginAgentPost'])->name('agent.login.post');
Route::post('/logout-agent', [AgentController::class, 'logoutAgent'])->name('agent.logout');

Route::get('/register-admin',[AdminController::class,'registerAdmin'])->name('admin.register');
Route::post('/regiser-admin',[AdminController::class,'registerAdminPost'])->name('admin.register.post');
Route::get('/login-admin',[AdminController::class,'loginAdmin'])->name('admin.login');
Route::post('/login-admin',[AdminController::class,'login_admin_post'])->name('admin.login.post');
Route::post('/logout-admin', [AdminController::class, 'logoutAdmin'])->name('admin.logout');
Route::get('/admin-categories',[AdminController::class,'CategoriesTable'])->name('categories.admin');
Route::get('/admin-products',[AdminController::class,'products_table'])->name('products.admin');
Route::get('/admin-agents',[AdminController::class,'agents_table'])->name('agents.admin');



//  ->middleware('admin');
//  public function adminDashboard(){
//     return view('layouts.admin_base');
//     }

// Route::get('/store', function (){
//     return view('agent_store');
// });
Route::get('/products-table', function (){
    return view('list_products');
});
// ---------------------------------------------------------------------.,,,,--------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------------------
Route::get('/insert-category',[CategoryController::class,'index2'])->name('category.display');
Route::post('/insert-category',[CategoryController::class,'categoryPost'])->name('category.post');
Route::get('/list-categories',[CategoryController::class,'CategoriesTable'])->name('categories.list');

Route::get('edit-category/{id}', [CategoryController::class, 'editCategory'])->name('category.edit');
Route::put('/category/{id}', [CategoryController::class, 'updateCategoryPost'])->name('category.update');
Route::get('delete-category/{id}',[CategoryController::class,'deleteCategory'])->name('category.delete');

//Route::get('/insert-products',[ProductController::class,'insertProducts'])->name('products.insert');

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/product/{id}',[ProductController::class,'additionalProducts'])->name('additional.products');
Route::get('/show-products',[ProductController::class,'showCategoriesAndBrands'])->name('category.brand');

Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');

// Route::get('/roles',[RolePermissionController::class,'openRolesPage'])->name('roles.index');
// Route::post('/roles',[RolePermissionController::class,'storeRole'])->name('roles.store');

// Route::get('/permissions',[RolePermissionController::class,'openPermissionsPage'])->name('permissions.index');
// Route::post('/permissions',[RolePermissionController::class,'storePermission'])->name('permissions.store');

// Route::get('/assign-permission-to-user',function(){
    // $user = User::find(1);
    // $permission = Permission::findByName('Update');

    // // $user->givePermissionTo($permission);
    // // dd('permission assigned');

    // // $user->revokePermissionTo($permission);
    // // dd($permission);

    // $role = Role::findByName('writer');
    // $role->givePermissionTo($permission);
    // dd('asssigned');
//     $user = User::find(1);
//     $role = Role::findByName('admin');
//     $user->assignRole($role);
//     dd('assigned');
// });

