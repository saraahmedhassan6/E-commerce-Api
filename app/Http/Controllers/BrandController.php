<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::paginate(10);
        if ($brands) {
            return response()->json($brands, 200);
        }
        return response()->json('There are no brands');
    }

    public function show($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            return response()->json($brand, 200);
        }
        return response()->json('brand not found');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:brands,name'
            ]);
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->save();
            return response()->json('brand added', 201);

        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required'
            ]);
            $Brand = Brand::find($id);
            if ($Brand) {
                $Brand->name = $request->name;
                $Brand->save();
                return response()->json('Brand updated', 200);
            } else {
                return response()->json('Brand not found');
            }
        } catch (Exception $e) {
            return response()->json($e, 500);
        }

    }

    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);
            if ($brand) {
                $brand->delete();
                return response()->json('brand deleted', 200);
            } else {
                return response()->json('brand not found');
            }
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
