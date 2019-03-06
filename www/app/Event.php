<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * @var string
     */
    protected $table = 'event';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => true
    ];

    /**
     * Get the Place of the Event.
     */
    public function place()
    {
        return $this->belongsTo(\App\Place::class);
    }

    /**
     * Get the Comments for the Event.
     */
    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }
}
