<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['school_id','branch_id','name','level'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function classrooms(): HasMany { return $this->hasMany(Classroom::class); }
}
