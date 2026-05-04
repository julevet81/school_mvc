<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['school_id','branch_id','user_id','admission_application_id','student_no','full_name','date_of_birth','gender','enrollment_date','status','blood_group','allergies','medical_notes'];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date','enrollment_date' => 'date'];
    }

    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function admissionApplication(): BelongsTo { return $this->belongsTo(AdmissionApplication::class); }
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentProfile::class, 'parent_student', 'student_id', 'parent_id')
            ->withPivot(['relationship', 'is_primary', 'financially_responsible'])
            ->withTimestamps();
    }
    public function enrollments(): HasMany { return $this->hasMany(StudentEnrollment::class); }
}
