<?php

declare(strict_types=1);

namespace App\Http\Requests\Grade;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $gradeId = $this->route('grade')->id;

        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('school_id', $this->integer('school_id'))],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('grades', 'name')
                    ->where('school_id', $this->integer('school_id'))
                    ->where('branch_id', $this->integer('branch_id'))
                    ->ignore($gradeId),
            ],
            'level' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }
}
