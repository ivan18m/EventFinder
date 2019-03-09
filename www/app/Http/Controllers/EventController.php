<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Event;
use App\Place;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Event::with('place')->with('comments')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $place)
    {
        $request->request->add(['place' => $place]);

        $data = $request->validate([
            'name' => 'required|string|min:2|max:30',
            'place' => 'required|integer|exists:place,id',
            'description' => 'nullable|string',
            'starts_at' => 'required|string',
            'duration' => 'nullable|string'
        ]);

        $event = new Event;
        $event->name = $request->name;
        $event->description = $request->description;
        $event->starts_at = $request->starts_at;
        $event->duration = $request->duration;
        $event->place()->associate($request->place);
        $event->save();

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
        return Event::findOrFail($id);
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
        $event = Event::find($id);

        $event->name = $request->name;
        $event->place()->associate($request->place);
        $event->desctiption = $request->description;
        $event->starts_at = $request->starts_at;
        $event->duration = $request->duration;

        $event->save();

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
        $event = Event::find($id);
        $event->delete();

        return ['status' => 'success'];
    }

    /**
     * Find Events within a radius
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
