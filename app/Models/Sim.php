<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Sim extends Model {
    protected $fillable = [
        'station_id',
        'name',
        'phone',
        'imei',
        'ip',
        'status'
    ];


    public function ussds() {
        return $this->hasMany(Ussd::class, 'sim_id');
    }
}
