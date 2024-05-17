<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EquipmentStoreRequest
 *
 * This class represents a form request for storing an equipment. It defines the validation rules
 * that apply to the request data.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentStoreRequest extends FormRequest
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
            'equipmentGroupId' => 'required|integer|exists:equipment_groups,id',
            'operational' => 'required|bool',
        ];
    }
}
