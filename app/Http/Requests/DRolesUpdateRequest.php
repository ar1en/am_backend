<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DRolesUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public static function rules(): array
    {
        return [
            'parentRole' => ['nullable','uuid', 'exists:ent_roles,id'],
            'department' => ['nullable', 'uuid'],
            'object' => ['nullable', 'uuid'],
            'process' => ['nullable', 'uuid'],
            'name' => ['required', 'string'],
            'rel_users' => ['nullable'],
            'rel_users.*.*' => ['nullable','uuid','exists:ent_users,id'],
        ];
    }
}
