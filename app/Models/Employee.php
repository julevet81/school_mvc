<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = ['school_id', 'branch_id', 'user_id', 'department_id', 'employee_no', 'job_title', 'hire_date', 'employment_type', 'status'];

    protected function casts(): array
    {
        return ['hire_date' => 'date'];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
