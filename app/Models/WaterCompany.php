<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class WaterCompany extends Model
{
    protected $connection = 'mongodb_water';
    protected $collection = 'companies';
}
