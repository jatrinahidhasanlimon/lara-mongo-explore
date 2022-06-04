<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class WaterTicket extends Model
{
    protected $connection = 'mongodb_water';
    protected $collection = 'tickets';
    public function company()
    { 
        //  return $this->embedsMany(Book::class, 'local_key');
        return $this->belongsTo(WaterCompany::class,'companyId');
    }
    protected $dates = ['createdAt','tripDateTime','ticketDateTime'];
}
