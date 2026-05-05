<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class School extends Model
{
    use HasFactory;
    use SoftDeletes, HasTranslations;
    

    protected $fillable = [
        'code',
        'name',
        'legal_name',
        'email',
        'phone',
        'country',
        'timezone',
        'currency',
        'settings',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            //'name' => 'array',
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}