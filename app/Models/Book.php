<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class Book extends Model
{
    protected $connection = 'mongodb_laravel_sample_database';
    protected $collection = 'books';
    // protected $dates = ['createdAt'];
    protected $dates = ['created_at','check_date'];
}
