<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenceNotificationLog extends Model
{
    protected $fillable = [
        'attendance_session_id',
        'student_id',
        'parent_id',
        'channel',
        'recipient',
        'status',
        'message',
        'sent_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime'];
    }

    public function attendanceSession(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
}
