<?php

declare(strict_types=1);

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $schoolId = $this->route('school')->id;
        $branchId = $this->route('branch')->id;

        return [
            'code' => ['required', 'string', 'max:30', 'alpha_dash', Rule::unique('branches', 'code')->where('school_id', $schoolId)->ignore($branchId)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'is_main' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtolower((string) $this->input('code')),
            'is_main' => $this->boolean('is_main'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
