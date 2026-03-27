<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $fillable = ["resident_id","sender","tracking_number","arrived_at","size","picked_up","picked_up_at"];

    public function resident()
    {
        return $this -> belongsTo(Resident::class);
    }
}
