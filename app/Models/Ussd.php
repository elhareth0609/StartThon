<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Ussd extends Model {
    protected $fillable = [
        'sim_id',
        'ussd_code',
        'status'
    ];

    public function sim() {
        return $this->belongsTo(Sim::class, 'sim_id');
    }

}
