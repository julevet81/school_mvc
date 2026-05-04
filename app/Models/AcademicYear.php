<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['school_id','branch_id','name','start_date','end_date','is_current'];
    protected function casts(): array { return ['start_date' => 'date','end_date' => 'date','is_current' => 'boolean']; }
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function semesters(): HasMany { return $this->hasMany(Semester::class); }
}
