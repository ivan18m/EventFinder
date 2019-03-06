<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'place';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => true
    ];

    /**
     * Get the Events for the Place.
     */
    public function events()
    {
        return $this->hasMany(\App\Event::class);
    }
}
