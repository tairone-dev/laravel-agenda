<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $now = strtotime('now');

        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'start_date'  => 'required|date_format:Y-m-d H:i|after:' . $now . '|before:deadline',
            'deadline'    => 'required|date_format:Y-m-d H:i|after:start_date',
            'end_date'    => 'nullable|date_format:Y-m-d H:i',
            'user_id'     => 'required|exists:users,id',
            'status_id'   => 'required|exists:statuses,id',
        ];
    }

    /**
     * Failed validation disable redirect
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
