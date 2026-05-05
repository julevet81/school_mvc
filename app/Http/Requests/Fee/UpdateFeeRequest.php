<?php

declare(strict_types=1);

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $feeId = $this->route('fee')->id;

        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'branch_id' => ['required', 'integer', Rule::exists('branches', 'id')->where('school_id', $this->integer('school_id'))],
            'code' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('fees', 'code')
                    ->where('school_id', $this->integer('school_id'))
                    ->where('branch_id', $this->integer('branch_id'))
                    ->ignore($feeId),
            ],
            'name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0'],
            'frequency' => ['required', 'string', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper((string) $this->input('code')),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
