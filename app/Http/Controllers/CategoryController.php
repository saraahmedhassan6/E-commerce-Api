<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Validation\ValidationException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        if ($categories) {
            return response()->json($categories, 200);
        }
        return response()->json('Products not found');
    }

    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category, 200);
        }
        return response()->json('category not found');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name',
                'image' => 'required'
            ]);
            $category = new Category();
            $category->name = $request->name;
            $uploadedImageUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'CategoryFolder',
            ]);
            $securePath = $uploadedImageUrl->getSecurePath();
            $publicID = $uploadedImageUrl->getPublicId();
            $category->image = $securePath;
            $category->public_id = $publicID;
            $category->save();
            return response()->json('Category added', 201);

        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'image' => 'nullable|image',
            ]);
            $category = Category::findOrFail($id);
            Cloudinary::destroy($category->public_id);

            if ($category) {
                $category->name = $request->name;
                if ($request->hasFile('image')) {
                    Cloudinary::destroy($category->public_id);
                    $uploadedImageUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                        'folder' => 'CategoryFolder',
                    ]);
                    $securePath = $uploadedImageUrl->getSecurePath();
                    $publicID = $uploadedImageUrl->getPublicId();
                    $category->image = $securePath;
                    $category->public_id = $publicID;
                }
                $category->save();
                return response()->json('Category updated', 200);
            } else {
                return response()->json('Category not found');
            }
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $category = Category::find($id);

            if ($category) {

                if ($category->image) {
                    Cloudinary::destroy($category->public_id);
                }
                $category->delete();

                return response()->json('Category deleted', 200);
            } else {
                return response()->json('Category not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
