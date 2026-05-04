<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiSnapshot extends Model
{
    protected $fillable = [
        'school_id','branch_id','snapshot_date','total_students','active_teachers','fee_due_total','fee_collected_total','new_leads',
    ];

    protected function casts(): array
    {
        return ['snapshot_date' => 'date'];
    }
}
