<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PreferenceStoreRequest
 *
 * This class represents a form request for storing a preference. It defines the validation rules
 * that apply to the request data.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class PreferenceStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array Returns an array of validation rules for the request data.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'manipName' => 'required|string|max:100',
            'locationId' => 'required|integer|exists:locations,id',
            'equipmentIds' => ['required', 'array', 'min:1'],
            'equipmentIds.*' => 'integer|exists:equipment,id',
            'teamIds' => [
                'array',
                function ($attribute, $value, $fail) {
                    $userId = request()->input('userId', auth()->user()?->id);
                    if (in_array($userId, $value)) {
                        $fail('The owner of the manip cannot be in the team.');
                    }
                },
            ],
            'teamIds.*' => 'integer|exists:users,id',
            'userId' => 'nullable|sometimes|integer|exists:users,id',
        ];
    }
}
