<?php

declare(strict_types=1);

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('school_id', $this->integer('school_id'))],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'attendance_date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:30'],
            'method' => ['required', 'string', 'max:30'],
            'attendances' => ['required', 'array', 'min:1'],
            'attendances.*.student_id' => ['required', 'integer', 'exists:students,id'],
            'attendances.*.status' => ['required', 'string', 'in:present,absent,late,excused'],
            'attendances.*.remarks' => ['nullable', 'string'],
        ];
    }
}
