<?php

declare(strict_types=1);

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $classroomId = $this->route('classroom')->id;

        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('school_id', $this->integer('school_id'))],
            'grade_id' => ['required', 'integer', Rule::exists('grades', 'id')->where('school_id', $this->integer('school_id'))->where('branch_id', $this->integer('branch_id'))],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('classrooms', 'name')
                    ->where('school_id', $this->integer('school_id'))
                    ->where('branch_id', $this->integer('branch_id'))
                    ->where('grade_id', $this->integer('grade_id'))
                    ->ignore($classroomId),
            ],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
