<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [\App\Models\Branch::class, $this->route('school')]);
    }

    public function rules(): array
    {
        $schoolId = $this->route('school')->id;

        return [
            'code'      => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('branches', 'code')->where('school_id', $schoolId)
            ],
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'address'   => ['nullable', 'string', 'max:1000'],
            'is_main'   => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['code' => strtolower((string) $this->code)]);
    }
}
