<?php

declare(strict_types=1);

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassroomRequest extends FormRequest
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
            'grade_id' => ['required', 'integer', Rule::exists('grades', 'id')->where('school_id', $this->integer('school_id'))->where('branch_id', $this->integer('branch_id'))],
            'name' => ['required', 'string', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
