<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionDocument extends Model
{
    use HasFactory;

    protected $fillable = ['admission_application_id','document_type','file_path','mime_type','file_size','uploaded_by','verified_at','verified_by'];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    public function application(): BelongsTo { return $this->belongsTo(AdmissionApplication::class, 'admission_application_id'); }
}
