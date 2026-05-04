<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionApplication extends Model
{
    use HasFactory;

    protected $fillable = ['school_id','branch_id','application_no','status','student_first_name','student_last_name','student_date_of_birth','student_gender','target_grade','submitted_by','reviewed_by','reviewed_at','interview_at','placement_result','registration_fee','notes'];

    protected function casts(): array
    {
        return ['student_date_of_birth' => 'date','reviewed_at' => 'datetime','interview_at' => 'datetime','registration_fee' => 'decimal:2'];
    }

    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function submitter(): BelongsTo { return $this->belongsTo(User::class, 'submitted_by'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function documents(): HasMany { return $this->hasMany(AdmissionDocument::class); }
    public function workflowLogs(): HasMany { return $this->hasMany(AdmissionWorkflowLog::class); }
}
