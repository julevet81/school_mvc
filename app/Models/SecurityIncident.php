<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityIncident extends Model
{
    protected $fillable = ['school_id','branch_id','user_id','type','severity','description','ip_address','context','detected_at'];

    protected function casts(): array
    {
        return ['context' => 'array', 'detected_at' => 'datetime'];
    }
}
