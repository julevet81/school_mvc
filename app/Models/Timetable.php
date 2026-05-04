<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = ['school_id','branch_id','academic_year_id','semester_id','name','is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function entries(): HasMany { return $this->hasMany(TimetableEntry::class); }
}
