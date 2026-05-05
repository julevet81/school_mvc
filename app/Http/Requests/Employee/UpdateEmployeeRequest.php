<?php

declare(strict_types=1);

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')->id;

        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('school_id', $this->integer('school_id'))],
            'employee_no' => [
                'required',
                'string',
                'max:40',
                Rule::unique('employees', 'employee_no')
                    ->where('school_id', $this->integer('school_id'))
                    ->where('branch_id', $this->integer('branch_id'))
                    ->ignore($employeeId),
            ],
            'job_title' => ['nullable', 'string', 'max:120'],
            'hire_date' => ['nullable', 'date'],
            'employment_type' => ['required', 'string', 'max:30'],
            'status' => ['required', 'string', 'max:30'],
        ];
    }
}
