<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'street' => 'required',
                'building' => 'required',
                'area' => 'required',
            ]);
            $Location = new Location();
            $Location->street = $request->street;
            $Location->building = $request->building;
            $Location->area = $request->area;
            $Location->user_id = Auth::id();

            $Location->save();
            return response()->json('Location added', 201);

        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'street' => 'required',
                'building' => 'required',
                'area' => 'required',
            ]);
            $Location = Location::find($id);
            if ($Location) {
                $Location->street = $request->street;
                $Location->building = $request->building;
                $Location->area = $request->area;
                $Location->save();
                return response()->json('Location updated', 200);
            } else {
                return response()->json('Location not found');
            }
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $Location = Location::find($id);
            if ($Location) {
                $Location->delete();
                return response()->json('Location deleted', 200);
            } else {
                return response()->json('Location not found');
            }
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }
}
