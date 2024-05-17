<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class LocationResource
 *
 * This class represents a location resource used to transform location model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class LocationResource extends JsonResource
{
    /**
     * Convert the location model instance to an array representation.
     *
     * This method converts the location model instance into an associative array representation,
     * which includes the 'id' and 'name' properties of the location, the ID of the associated site,
     * and the name of the associated site.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the location model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "siteId" => $this->site_id,
            "siteName" => $this->site->name,
        ];
    }
}
