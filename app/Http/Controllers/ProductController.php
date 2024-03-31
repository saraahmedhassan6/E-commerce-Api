<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        if ($products) {
            return response()->json($products, 200);
        }
        return response()->json('Products not found');
    }

    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json($product, 200);
        }
        return response()->json('Product not found');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'amount' => 'required|numeric',
                'discount' => 'required|numeric',
                'brand_id' => 'required|numeric',
                'category_id' => 'required|numeric',
                'images' => 'required'
            ]);
            $product = new Product();
            $product->name = $request->name;
            $product->is_trendy = $request->is_trendy;
            $product->is_available = $request->is_available;
            $product->price = $request->price;
            $product->amount = $request->amount;
            $product->discount = $request->discount;
            $product->brand_id = $request->brand_id;
            $product->category_id = $request->category_id;
            if ($request->hasFile('images')) {
                $photos = [];
                $publicID = [];
                foreach ($request->file('images') as $photo) {
                    $uploadedImageUrl = Cloudinary::upload($photo->getRealPath(), [
                        'folder' => 'ProductFolder',
                    ]);
                    $photos[] = $uploadedImageUrl->getSecurePath();
                    $publicID[] = $uploadedImageUrl->getPublicId();
                }

                $jsonEncodedPhotos = json_encode($photos);
                $jsonEncodedPublicID = json_encode($publicID);

                $product->images = $jsonEncodedPhotos;
                $product->public_id = $jsonEncodedPublicID;
            }

            $product->save();
            return response()->json('Product added', 201);

        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'amount' => 'required|numeric',
                'discount' => 'required|numeric',
                'brand_id' => 'required|numeric',
                'category_id' => 'required|numeric',
                'images.*' => 'nullable|image',
            ]);
            $product = Product::findOrFail($id);
            if ($product) {
                $product->name = $request->name;
                $product->is_trendy = $request->is_trendy;
                $product->is_available = $request->is_available;
                $product->price = $request->price;
                $product->amount = $request->amount;
                $product->discount = $request->discount;


                if ($request->hasFile('images')) {
                    if ($product->images) {
                        $existingPublicIDs = json_decode($product->public_id, true) ?? [];
                        foreach ($existingPublicIDs as $publicID) {
                            Cloudinary::destroy($publicID);
                        }
                    }
        
                    $photos = [];
                    $publicIDs = [];
                    foreach ($request->file('images') as $photo) {
                        $uploadedImageUrl = Cloudinary::upload($photo->getRealPath(), [
                            'folder' => 'ProductFolder',
                        ]);
                        $photos[] = $uploadedImageUrl->getSecurePath();
                        $publicIDs[] = $uploadedImageUrl->getPublicId();
                    }
                    $jsonEncodedPhotos = json_encode($photos);
                    $jsonEncodedPublicIDs = json_encode($publicIDs);
        
                    $product->images = $jsonEncodedPhotos;
                    $product->public_id = $jsonEncodedPublicIDs;
                }
        
                $product->save();
                return response()->json('Product updated', 200);
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
            $product = Product::find($id);

            if ($product) {
                if ($product->images) {
                    $existingPublicIDs = json_decode($product->public_id, true) ?? [];
                    foreach ($existingPublicIDs as $publicID) {
                        Cloudinary::destroy($publicID);
                    }
                }
                $product->delete();

                return response()->json('Product deleted', 200);
            } else {
                return response()->json('Product not found', 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
}
