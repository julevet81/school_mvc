<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentProfile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'parents';

    protected $fillable = ['school_id','branch_id','user_id','parent_no','full_name','email','phone','occupation','portal_enabled'];

    protected function casts(): array
    {
        return ['portal_enabled' => 'boolean'];
    }

    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
            ->withPivot(['relationship', 'is_primary', 'financially_responsible'])
            ->withTimestamps();
    }
}
