<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 *
 * This class represents a controller for managing users via API endpoints. It includes methods
 * for retrieving, updating, and deleting users. Each method contains authorization checks
 * and validation rules for handling requests related to users.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class UserController extends Controller
{

    /**
     * Retrieve a list of all users in JSON format.
     * 
     * This method fetches all users from the database, ordered alphabetically by name. Before fetching the data,
     * it checks whether the user has the authorization to view any user. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of users, 
     *                                       each wrapped by the UserResource, or a 403 Forbidden status if not authorized.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
            
        $users = UserResource::collection(User::orderBy('name', 'asc')->get());
        
        return response()->json($users);
    }
    
    /**
     * Retrieve a list of all users except the current user in JSON format.
     * 
     * This method fetches all users except the current user from the database, ordered alphabetically by name. Before fetching the data,
     * it checks whether the user has the authorization to view any user. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of users except the current user, 
     *                                       each wrapped by the UserResource, or a 403 Forbidden status if not authorized.
     */
    public function indexOtherUsers()
    {
        $this->authorize('viewAny', User::class);
            
        $users = UserResource::collection(User::where('id', '!=', auth()->user()->id)->orderBy('name', 'asc')->get());
        
        return response()->json($users);
    }

    /**
     * Change the color associated with a user.
     *
     * This method changes the color associated with the specified user to the provided color value. 
     * If the user is not found, a JSON response with a 404 Not Found status is returned. 
     * If the color value is not provided, a JSON response with a 404 Not Found status is returned. 
     * Upon successful update of the user's color, a JSON response with a 200 OK status is returned, 
     * containing a message indicating the successful color update and the updated user object.
     *
     * @param User $user The user model instance whose color is to be changed.
     * @param string $color The new color value to be associated with the user.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status, 
     *                                       containing a message indicating the successful color update 
     *                                       and the updated user object, or a 404 Not Found status 
     *                                       if the user or color is not found.
     */
    public function changeColor(User $user, string $color)
    {
        if (!$user) {
            return response()->json(['message' => 'User not find'], 404);
        }

        if (!$color) {
            return response()->json(['message' => 'Color not find'], 404);
        }

        $decodedColor = urldecode($color);

        $user->color = $decodedColor;
        $user->save();

        return response()->json(['message' => 'Couleur mise à jour avec succès', 'user' => $user]);
    }

    /**
     * Display the current user.
     *
     * This method retrieves and displays the details of the current user. 
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified user wrapped by the UserResource.
     */
    public function showCurrentUser()
    {
        $user = UserResource::make(auth()->user());

        return response()->json($user);
    }

    /**
     * Display the specified user.
     *
     * This method retrieves and displays the details of the specified user. 
     * Authorization is checked to ensure the user has permission to view the user.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * 
     * @param User $user The user model instance to be displayed.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified user wrapped by the UserResource, or a 403 
     *                                       Forbidden status if the user is not authorized to view the user.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return response()->json(UserResource::make($user));
    }

    /**
     * Update the specified user.
     *
     * This method updates the details of the specified user using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update users.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * 
     * @param UserUpdateRequest $request The request object containing the validation rules for updating a user.
     * @param User $user The user model instance to be updated.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update,
     *                                       a 403 Forbidden status if the user is not authorized to update users,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(UserUpdateRequest $request, User $user) {

        $this->authorize('update', User::class);

        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "validated" => $request->validated,
        ]);

        $user->syncRoles([]);
        $user->assignRole($request->role);

        return response()->json();
    }

    /**
     * Delete the specified user.
     *
     * This method deletes the specified user from the database. 
     * Authorization is checked to ensure the user has permission to delete users.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param User $user The user model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete users.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);

        $user->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
