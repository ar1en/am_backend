<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DVacanciesDefaultRequest extends FormRequest
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
            'desc' => ['nullable','string'],
            'vacancies_types' => ['uuid','exists:ent_vacancies_types,id','required'],
            //'departments'=> ['uuid','exists:ent_departments,id'],
            'department'=> ['required','uuid','exists:ent_departments,id'],
            //'validFrom' => 'data',
            //'validUntil' => 'data',
        ];
    }
}
