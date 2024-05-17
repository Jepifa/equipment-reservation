<?php

namespace App\Http\Resources;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ManipResource
 *
 * This class represents a manip resource used to transform manip model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class ManipResource extends JsonResource
{

    /**
     * Convert the manipulation model instance to an array representation.
     *
     * This method converts the manipulation model instance into an associative array representation,
     * which includes the 'id', 'name', 'userId', 'userName', and 'userColor' properties of the manipulation,
     * along with the ID and name of the associated user, the location ID and name, the site name,
     * an array of equipment IDs associated with the manipulation, an array of equipment resources,
     * an array of team IDs associated with the manipulation, an array of user resources representing the team members,
     * the 'beginDate', and the 'endDate'.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the manipulation model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
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
            "beginDate"=>$this->begin_date,
            "endDate"=>$this->end_date,
        ];
    }
}
