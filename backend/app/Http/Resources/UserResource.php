<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * This class represents a user resource used to transform user model instances 
 * into a JSON representation.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class UserResource extends JsonResource
{

    /**
     * Convert the user model instance to an array representation.
     *
     * This method converts the user model instance into an associative array representation,
     * which includes the 'id', 'name', 'email', 'validated', 'role', 'permissions', and 'color' properties of the user.
     * The 'validated' property indicates whether the user account is validated or not.
     * The 'role' property contains an array of role names assigned to the user.
     * The 'permissions' property contains an array of permissions granted to the user via roles.
     *
     * @param Request $request The HTTP request instance.
     * 
     * @return array Returns an associative array representation of the user model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "validated" => $this->validated ? true : false,
            "role" => $this->getRoleNames(),
            "permissions" => $this->getPermissionsViaRoles()->pluck("name"),
            "color" => $this->color,
        ];
    }
}
