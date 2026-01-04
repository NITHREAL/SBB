<?php

namespace Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderContact extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id',
        'name',
        'phone',
        'email',
        'address',
        'apartment',
        'floor',
        'entrance',
        'latitude',
        'longitude',
    ];
}
