<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Place;
use App\Queries\WithinRadius;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Place::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:30',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-85.0511288,85.0511288',
        ]);
        
        $request->longitude = round($request->longitude, 7);
        $request->latitude = round($request->latitude, 7);

        $exists = Place::where([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ])->first();

        if($exists !== NULL) {
            return \Response::json([
                'status' => 'error', 
                'message' => 'Place with same coordinates already exists'
            ], 400);
        }

        $place = new Place;
        $place->name = $data["name"];
        $place->longitude = $data["longitude"];
        $place->latitude = $data["latitude"];
        $place->save();

        return ['status' => 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Place::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:30',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-85.0511288,85.0511288',
        ]);

        $place = Place::find($id);

        $place->name = $request->name;
        $place->longitude = $request->longitude;
        $place->latitude = $request->latitude;

        $place->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = Place::find($id);
        $place->delete();
        return ['status' => 'success'];
    }

    /**
     * Find Places within a radius
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function radius(Request $request)
    {
        $data = $request->validate([
            'radius' => 'required|numeric|min:0',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-85.0511288,85.0511288',
            'unit' => 'nullable|string|min:2|max:2'
        ]);

        $unit = 'km';
        $units = ["km" => 6371, "mi" => 3959];

        if(\property_exists($request, 'unit')) {
            if(\in_array($unit, $units)) {
                $unit = $request->unit;
            }
        }

        $wr = new WithinRadius;
        return $wr->getPlaces($request->latitude, $request->longitude, $request->radius, $unit);
    }
}
