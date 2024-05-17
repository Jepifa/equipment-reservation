<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PreferenceResource
 *
 * This class represents a preference resource used to transform preference model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class PreferenceResource extends JsonResource
{

    /**
     * Convert the preference model instance to an array representation.
     *
     * This method converts the preference model instance into an associative array representation,
     * which includes the 'id', 'name', 'manipName', 'userId', 'userName', and 'userColor' properties of the preference,
     * along with the ID and name of the associated user, the location ID and name, the site name,
     * an array of equipment IDs associated with the preference, an array of equipment resources,
     * an array of team IDs associated with the preference, and an array of user resources representing the team members.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the preference model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "manipName" => $this->manip_name,
            "userId" => $this->user_id,
            "userName" => $this->user->name,
            "userColor" => $this->user->color,
            "locationId" => $this->location_id,
            "locationName" => $this->location->name,
            "siteName"=>$this->location->site->name,
            "equipmentIds"=>collect($this->equipment)->pluck('id')->toArray(),
            "equipments"=>EquipmentResource::collection($this->equipment),
            "teamIds"=>collect($this->team)->pluck('id')->toArray(),
            "team"=>UserResource::collection($this->team),
        ];
    }
}
