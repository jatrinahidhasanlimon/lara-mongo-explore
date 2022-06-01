<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class IntercityBookedHistory extends Model
{
    protected $connection = 'mongodb_intercity';
    protected $collection = 'bookedhistories';
    use HasFactory;
}
