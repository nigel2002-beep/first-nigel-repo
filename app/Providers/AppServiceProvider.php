<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Using view()->composer to delay the retrieval of data until the view is composed
        view()->composer('*', function ($view) {
            // Check if the user is authenticated before trying to access Auth::user()
            if (Auth::check()) {
                $productCount = Product::count();
                $agent = User::where('is_admin', 1)->count();
                $category = Category::count();
                $view->with('productCount', $productCount)->with('agent',$agent)->with('category',$category);
            }
        });
    }

}
