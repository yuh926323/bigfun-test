<?php

namespace App\Models;

use App\Models\Traits\LikeScope;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use LikeScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'distict',
        'address ',
        'lat',
        'lng',
        'houseHolds',
        'persons',
        'floors',
        'progress',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
