<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionWorkflowLog extends Model
{
    use HasFactory;

    protected $fillable = ['admission_application_id','from_status','to_status','actor_id','comment'];

    public function application(): BelongsTo { return $this->belongsTo(AdmissionApplication::class, 'admission_application_id'); }
}
