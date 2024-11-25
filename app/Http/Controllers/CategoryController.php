<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index2(){
        return view('category_insert');

    }
    public function categoryPost(Request $request){
        $request->validate([
            "category_name"=> 'required|unique:categories',
        ]);
        $category = new Category ();
        $category->category_name = $request->category_name;
        if($category->save()){
            return redirect()->route('user.dashboard')->with ('success','category inserted successfully ');
        }
         return redirect()->route('category.display')->with ('error','Failed to insert category because it exists');
    }
    public function categoriesTable(){
        $categories = Category::all();
        return view('list_categories',compact('categories'));

    }

    public function editCategory($id){
        $category = Category::findOrFail($id);
        return view('category_edit', compact('category'));
    }

    // Update the category
    public function updateCategoryPost(Request $request, $id){
        $category = Category::findOrFail($id);

        $request->validate([
            "category_name" => 'required|unique:categories,category_name,' . $category->id
        ]);

        $category->category_name = $request->category_name;

        if($category->save()){
            return redirect()->route('user.dashboard')->with('success', 'Category updated successfully.');
        }
        return back()->withErrors('Failed to update category.');
    }
    public function deleteCategory($id)
    {
        $category = Category::find($id);
            $category->delete();
            return redirect('/user')->with('success', 'Category deleted successfully');
    }
}
