<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class EquipmentResource
 *
 * This class represents an equipment resource used to transform equipment model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentResource extends JsonResource
{
    /**
     * Convert the equipment model instance to an array representation.
     *
     * This method converts the equipment model instance into an associative array representation,
     * which includes the 'id' and 'name' properties of the equipment, whether it is operational or not,
     * the ID of the associated equipment group, the name of the associated equipment group,
     * and the name of the category to which the equipment group belongs.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the equipment model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "operational" => $this->operational ? true : false,
            "equipmentGroupId" => $this->equipment_group_id,
            "equipmentGroupName" => $this->equipmentGroup->name,
            "categoryName" => $this->equipmentGroup->category->name,
        ];
    }
}
