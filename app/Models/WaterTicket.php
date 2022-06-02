<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class WaterTicket extends Model
{
    protected $connection = 'mongodb_water';
    protected $collection = 'tickets';
    protected $dates = ['createdAt'];
}
