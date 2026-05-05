<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = ['school_id','branch_id','academic_year_id','section_id','attendance_date','type','method','recorded_by'];
    protected function casts(): array { return ['attendance_date' => 'date']; }
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function section(): BelongsTo { return $this->belongsTo(Section::class); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
    public function studentAttendances(): HasMany { return $this->hasMany(StudentAttendance::class); }
    public function staffAttendances(): HasMany { return $this->hasMany(StaffAttendance::class); }
    public function absenceNotificationLogs(): HasMany { return $this->hasMany(AbsenceNotificationLog::class); }
}
