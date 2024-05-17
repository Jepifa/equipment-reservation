<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CategoryResource
 *
 * This class represents a category resource used to transform category model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class CategoryResource extends JsonResource
{
    /**
     * Convert the equipment category model instance to an array representation.
     *
     * This method converts the equipment category model instance into an associative array representation,
     * which includes the 'id' and 'name' properties of the equipment category, as well as a collection 
     * of equipment groups associated with the category, represented as EquipmentGroupResource instances.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the equipment category model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "equipmentGroups" => EquipmentGroupResource::collection($this->groups),
        ];
    }
}
