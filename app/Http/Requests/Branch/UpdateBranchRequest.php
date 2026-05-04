<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', [$this->route('branch'), $this->route('school')]);
    }

    public function rules(): array
    {
        $schoolId = $this->route('school')->id;
        $branchId = $this->route('branch')->id;

        return [
            'code'      => [
                'sometimes',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('branches', 'code')->where('school_id', $schoolId)->ignore($branchId)
            ],
            'name'      => ['sometimes', 'string', 'max:255'],
            'email'     => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'address'   => ['nullable', 'string', 'max:1000'],
            'is_main'   => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('code')) {
            $this->merge(['code' => strtolower((string) $this->code)]);
        }
    }
}