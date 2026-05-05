<?php

declare(strict_types=1);

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $studentId = $this->route('student')->id;

        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('school_id', $this->integer('school_id'))],
            'student_no' => [
                'required',
                'string',
                'max:40',
                Rule::unique('students', 'student_no')
                    ->where('school_id', $this->integer('school_id'))
                    ->where('branch_id', $this->integer('branch_id'))
                    ->ignore($studentId),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'max:20'],
            'enrollment_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:30'],
            'blood_group' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'string'],
            'medical_notes' => ['nullable', 'string'],
        ];
    }
}
