<?php

namespace App\Http\Requests\School;
 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
 
final class UpdateSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('school'));
    }
 
    public function rules(): array
    {
        $schoolId = $this->route('school')->id;
 
        return [
            'code'       => ['sometimes', 'string', 'max:30', 'alpha_dash', Rule::unique('schools', 'code')->ignore($schoolId)],
            'name'       => ['sometimes', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'email'      => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'country'    => ['sometimes', 'string', 'size:2', 'alpha'],
            'timezone'   => ['sometimes', 'string', 'max:64', Rule::in(\DateTimeZone::listIdentifiers())],
            'currency'   => ['sometimes', 'string', 'size:3', 'alpha'],
            'settings'   => ['nullable', 'array'],
            'is_active'  => ['sometimes', 'boolean'],
        ];
    }
 
    protected function prepareForValidation(): void
    {
        $merge = [];
 
        if ($this->filled('country'))  $merge['country']  = strtoupper((string) $this->country);
        if ($this->filled('currency')) $merge['currency'] = strtoupper((string) $this->currency);
        if ($this->filled('code'))     $merge['code']     = strtolower((string) $this->code);
 
        $this->merge($merge);
    }
}