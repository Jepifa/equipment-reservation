<?php

namespace App\Http\Requests;

use App\Rules\CheckDateHours;
use App\Rules\UniqueEquipmentForManip;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ManipStoreRequest
 *
 * This class represents a form request for storing a manip. It defines the validation rules
 * that apply to the request data.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class ManipStoreRequest extends FormRequest
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
            'locationId' => 'required|integer|exists:locations,id',
            'beginDate' => ['required', new CheckDateHours],
            'endDate' => ['required', 'after:beginDate', new CheckDateHours],
            'equipmentIds' => ['required', 'array', 'min:1', new UniqueEquipmentForManip],
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
