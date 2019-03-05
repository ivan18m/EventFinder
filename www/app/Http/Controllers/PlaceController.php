<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Place;

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

        if(\property_exists($request, 'unit')) {
            if(\in_array($unit, $units)) {
                $unit = $request->unit;
            }
        }

        $units = ["km" => 6371, "mi" => 3959];

        $haversine = "
            :unit_value::numeric * 
            ATAN2(
                SQRT(
                    POW(
                        COS(RADIANS(:lat)) * 
                        SIN(RADIANS(:lng - p.longitude))
                    , 2) +
                    POW(
                        COS(RADIANS(p.latitude)) * 
                        SIN(RADIANS(:lat)) -
                        (
                            SIN(RADIANS(p.latitude)) * 
                            COS(RADIANS(:lat)) *
                            COS(RADIANS(:lng - p.longitude))
                        )
                    , 2)
                ),
                SIN(RADIANS(p.latitude)) * SIN(RADIANS(:lat)) +
                COS(RADIANS(p.latitude)) * COS(RADIANS(:lat)) * COS(RADIANS(:lng - p.longitude))
            )::numeric";

        $sql = "SELECT id, name, latitude, longitude, 
                :unit::varchar AS unit, 
                round($haversine, 2) AS distance
                FROM place p
                GROUP BY id
                HAVING round($haversine, 2) <= :radius
                ORDER BY distance";

        $params = [
            'lat' => $request->latitude, 
            'lng' => $request->longitude, 
            'radius' => $request->radius,
            'unit_value' => $units[$unit],
            'unit' => $unit
        ];

        return DB::select($sql, $params);
    }
}
