<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['classroom_id','name','homeroom_teacher_id'];

    public function classroom(): BelongsTo { return $this->belongsTo(Classroom::class); }
    public function homeroomTeacher(): BelongsTo { return $this->belongsTo(User::class, 'homeroom_teacher_id'); }
    public function enrollments(): HasMany { return $this->hasMany(StudentEnrollment::class); }
}
