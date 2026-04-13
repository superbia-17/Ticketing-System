<?php
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
 
class CategoryController extends Controller
{
    // Public: active categories for the submission form
    public function index()
    {
        return response()->json(
            Category::where('is_active', true)->get(['id', 'name', 'slug', 'description'])
        );
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
 
        return response()->json(Category::create($data), 201);
    }
 
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'is_active'   => 'boolean',
        ]);
 
        $category->update($data);
        return response()->json($category);
    }
 
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted.']);
    }
}


?>