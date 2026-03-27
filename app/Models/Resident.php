<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = ["name", "apartment", "email", "phone",];

    public function parcels(){
        return $this->hasMany(Parcel::class);
    }
}
