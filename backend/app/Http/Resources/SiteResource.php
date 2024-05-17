<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SiteResource
 *
 * This class represents a site resource used to transform site model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class SiteResource extends JsonResource
{
    /**
     * Convert the site model instance to an array representation.
     *
     * This method converts the site model instance into an associative array representation,
     * which includes the 'id' and 'name' properties of the site.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the site model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
        ];
    }
}
