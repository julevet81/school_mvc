<?php

declare(strict_types=1);

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['nullable', 'integer', 'exists:schools,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'status' => ['nullable', 'string', 'in:present,absent,late,excused'],
        ];
    }
}
