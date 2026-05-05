<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Branch;
use App\Models\School;
use Illuminate\Support\Collection;

class SchoolContext
{
    public static function schoolOptions(): Collection
    {
        return School::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public static function branchOptions(?int $schoolId = null): Collection
    {
        return Branch::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->with('school:id,name')
            ->orderBy('name')
            ->get(['id', 'school_id', 'name']);
    }
}
