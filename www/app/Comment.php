<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * @var string
     */
    protected $table = 'comment';

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
    public function event()
    {
        return $this->belongsTo(\App\Event::class);
    }

}
