<?php

namespace App\Http\Requests\School;

declare(strict_types=1);



use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\School::class);
    }

    public function rules(): array
    {
        return [
            'code'       => ['required', 'string', 'max:30', 'unique:schools,code', 'alpha_dash'],
            'name'       => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'email'      => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'country'    => ['required', 'string', 'size:2', 'alpha'],
            'timezone'   => ['required', 'string', 'max:64', Rule::in(\DateTimeZone::listIdentifiers())],
            'currency'   => ['required', 'string', 'size:3', 'alpha'],
            'settings'   => ['nullable', 'array'],
            'is_active'  => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'country'  => strtoupper((string) $this->country),
            'currency' => strtoupper((string) $this->currency),
            'code'     => strtolower((string) $this->code),
        ]);
    }
}
