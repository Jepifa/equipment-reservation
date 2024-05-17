<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;


/**
 * Class UserUpdateRequest
 *
 * This class represents a form request for updating a user. It defines the validation rules
 * that apply to the request data.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array Returns an array of validation rules for the request data.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'validated' => ['required', 'boolean'],
            'color' => ['nullable'],
            'role' => ['nullable', 'sometimes', 'array'],
            'role.*' => ['string', 'exists:roles,name']
        ];
    }
}
