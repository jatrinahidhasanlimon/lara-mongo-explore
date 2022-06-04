<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class WaterCompany extends Model
{
    protected $connection = 'mongodb_water';
    protected $collection = 'companies';
    protected $dates = ['createdAt'];
    public function tickets()
    { 
        //  return $this->embedsMany(Book::class, 'local_key');
        return $this->hasMany(WaterTicket::class,'companyId');
    }
}
