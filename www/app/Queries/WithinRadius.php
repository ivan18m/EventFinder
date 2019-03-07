<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

final class WithinRadius
{
    private $units = [
        "km" => 6371, 
        "mi" => 3959
    ];

    private $haversine = "
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


    /**
     * @param double $latitude
     * @param double $longitude
     * @param double $radius
     * @return \App\Place[]
     */
    public function getPlaces(float $latitude, float $longitude, float $radius, string $unit)
    {
        $sql = "SELECT id, name, latitude, longitude, 
            :unit::varchar AS unit, 
            round($this->haversine, 2) AS distance
            FROM place p
            GROUP BY id
            HAVING round($this->haversine, 2) <= :radius
            ORDER BY distance";

        $params = [
            'lat' => $latitude, 
            'lng' => $longitude, 
            'radius' => $radius,
            'unit_value' => $this->units[$unit],
            'unit' => $unit
        ];

        return DB::select($sql, $params);
    }

    /**
     * @param double $latitude
     * @param double $longitude
     * @param double $radius
     * @return \App\Event[]
     */
    public function getEvents(float $latitude, float $longitude, float $radius, string $unit)
    {
        $sql = "SELECT e.id, e.name, e.description, 
            e.starts_at, e.duration, e.created_at,
            p.name AS place, p.latitude, p.longitude, 
            :unit::varchar AS unit, 
            round($this->haversine, 2) AS distance
            FROM event e
            JOIN place p ON e.place_id=p.id
            GROUP BY e.id, p.name, p.latitude, p.longitude
            HAVING round($this->haversine, 2) <= :radius
            ORDER BY distance, e.starts_at";
            
        $params = [
            'lat' => $latitude, 
            'lng' => $longitude, 
            'radius' => $radius,
            'unit_value' => $this->units[$unit],
            'unit' => $unit
        ];
        
        return DB::select($sql, $params);
    }
}