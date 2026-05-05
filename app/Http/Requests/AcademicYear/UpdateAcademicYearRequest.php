<?php

declare(strict_types=1);

namespace App\Http\Requests\AcademicYear;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $academicYearId = $this->route('academic_year')->id;

        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('school_id', $this->integer('school_id'))],
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('academic_years', 'name')
                    ->where('school_id', $this->integer('school_id'))
                    ->where('branch_id', $this->integer('branch_id'))
                    ->ignore($academicYearId),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_current' => $this->boolean('is_current')]);
    }
}
