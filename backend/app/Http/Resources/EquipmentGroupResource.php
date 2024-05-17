<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class EquipmentGroupResource
 *
 * This class represents an equipment group resource used to transform equipment group model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentGroupResource extends JsonResource
{
    /**
     * Convert the equipment group model instance to an array representation.
     *
     * This method converts the equipment group model instance into an associative array representation,
     * which includes the 'id' and 'name' properties of the equipment group, the ID and name of the associated category,
     * and the collection of equipments associated with the group.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the equipment group model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "categoryId" => $this->category_id,
            "categoryName" => $this->category->name,
            "equipments" => $this->equipments,
        ];
    }
}

