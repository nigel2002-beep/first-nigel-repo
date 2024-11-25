<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
    return view('Admin.dashboard');
   }
   public function showCategoriesAndBrands()
   {

       $categories = Category::all();
       $products = Product::all();

       return view('products_show', compact('categories','products'));
   }
    public function insertProducts(){
        return view('product_insert');
    }
    public function displayProducts(){
        $categories = Category::all();

        return view('product_insert', compact('categories'));
    }
    public function addProductPost(Request $request){
        $request->validate([
            'product_title' => 'required|string|max:255',
            'description' => 'required|string',
            'keywords' => 'nullable|string',
            'category_id' => 'required',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_image2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_image3' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_price' => 'required|numeric',
        ]);
        $product = new Product();
        $product->product_title = $request->product_title;
        $product->description = $request->description;
        $product->category_id = $request->category_id;

        $handleImageUpload = function ($imageField) use ($request) {
            if ($request->hasFile($imageField)) {
                $image = $request->file($imageField);
                $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $imageExtension = $image->getClientOriginalExtension();
                $finalImageName = $imageName . '_' . time() . '.' . $imageExtension; // Ensure uniqueness
                $image->move(public_path('images'), $finalImageName);
                return $finalImageName;
            }
            return null;
        };

        $product->product_image = $handleImageUpload('product_image');
        $product->product_image2 = $handleImageUpload('product_image2');
        $product->product_image3 = $handleImageUpload('product_image3');
        $product->product_price = $request->product_price;
        if($product->save()){
            return redirect()->route('user.dashboard')->with ('success','product inserted successfully ');

        }
        return "An error has occured";
    }
    public function additionalProducts($id)
    {
        $product = Product::findOrFail($id);
    return view('additional_products', compact('product'));
    }
    public function search(Request $request)
    {
        $searchTerm = $request->input('search_data');

        // Retrieve products that match the search term
        $products = Product::where('product_title', 'LIKE', '%' . $searchTerm . '%')->get();

        // Return a view with the search results
        return view('products_search', compact('products'));
    }
    public function products_table(){
        $products = Product::all();
        return view('list_products',compact('products'));
    }
    public function editProduct($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all(); // Get all categories for the dropdown

        return view('products_edit', compact('product', 'categories'));
    }
    public function updateProductPost(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'product_title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'product_price' => 'required|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the product by ID or fail
        $product = Product::findOrFail($id);

        // Update the product details
        $product->product_title = $request->product_title;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->product_price = $request->product_price;

        // Handle image uploads
        $product->product_image = $this->handleImageUpload('product_image', $product->product_image, $request);
        $product->product_image2 = $this->handleImageUpload('product_image2', $product->product_image2, $request);
        $product->product_image3 = $this->handleImageUpload('product_image3', $product->product_image3, $request);

        // Save the product
        $product->save();

        // Redirect with success message
        return redirect()->route('products.edit', $product->id)->with('success', 'Product updated successfully');

    }

    // Helper function to handle image upload
    private function handleImageUpload($imageField, $currentImage, $request)
    {
        if ($request->hasFile($imageField)) {
            // Delete the old image if necessary
            if ($currentImage && file_exists(public_path('images/' . $currentImage))) {
                unlink(public_path('images/' . $currentImage));
            }

            // Handle the new image upload
            $image = $request->file($imageField);
            $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $imageExtension = $image->getClientOriginalExtension();
            $finalImageName = $imageName . '_' . time() . '.' . $imageExtension;

            // Move the uploaded file
            $image->move(public_path('images'), $finalImageName);

            return $finalImageName;
        }

        // If no new image, keep the existing image
        return $currentImage;
    }


    public function deleteProduct($id) {
        // Find the product by ID or fail
        $product = Product::findOrFail($id);

        // Optionally, delete the associated images
        if ($product->product_image && file_exists(public_path('images/' . $product->product_image))) {
            unlink(public_path('images/' . $product->product_image));
        }
        if ($product->product_image2 && file_exists(public_path('images/' . $product->product_image2))) {
            unlink(public_path('images/' . $product->product_image2));
        }
        if ($product->product_image3 && file_exists(public_path('images/' . $product->product_image3))) {
            unlink(public_path('images/' . $product->product_image3));
        }

        // Delete the product from the database
        $product->delete();

        // Redirect with a success message
        return redirect()->route('user.dashboard')->with('success', 'Product deleted successfully');
    }

}
