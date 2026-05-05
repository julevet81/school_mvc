<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $fillable = ['attendance_session_id','student_id','status','check_in_at','check_out_at','remarks'];
    protected function casts(): array { return ['check_in_at' => 'datetime:H:i', 'check_out_at' => 'datetime:H:i']; }
    public function session(): BelongsTo { return $this->belongsTo(AttendanceSession::class, 'attendance_session_id'); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
}
