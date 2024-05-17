<?php

namespace App\Http\Controllers\API;

use App\Models\Preference;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceStoreRequest;
use App\Http\Resources\PreferenceResource;
use Illuminate\Http\Response;

/**
 * Class PreferenceController
 *
 * This class represents a controller for managing preferences via API endpoints. It includes methods
 * for retrieving, storing, updating, and deleting preferences. Each method contains authorization checks
 * and validation rules for handling requests related to preferences.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class PreferenceController extends Controller
{
    /**
     * Retrieve a list of all preferences in JSON format.
     * 
     * This method fetches all preferences from the database, ordered alphabetically by name. Before fetching the data,
     * it checks whether the user has the authorization to view any preference. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of preferences, 
     *                                       each wrapped by the PreferenceResource, or a 403 Forbidden status if not authorized.
     */
    public function index()
    {
        $this->authorize('viewAny', Preference::class);
            
        $preferences = PreferenceResource::collection(Preference::orderBy('name', 'asc')->get());
        
        return response()->json($preferences);
    }

    /**
     * Retrieve a list of preferences associated with the authenticated user.
     *
     * This method fetches all preferences associated with the authenticated user from the database, 
     * ordered alphabetically by name. It verifies whether the authenticated user has the authorization 
     * to view any preferences before proceeding with the retrieval. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned. Upon successful retrieval, a JSON response 
     * with a 200 OK status is returned, containing the list of preferences wrapped by the PreferenceResource.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status, 
     *                                       containing the list of preferences associated with the authenticated user,
     *                                       each wrapped by the PreferenceResource, or a 403 Forbidden status 
     *                                       if the user is not authorized to view preferences.
     */
    public function indexByUser()
    {
        $this->authorize('viewAnyByUser', Preference::class);
            
        $preferences = PreferenceResource::collection(auth()->user()->preferences()->orderBy('name', 'asc')->get());
        
        return response()->json($preferences);
    }

    /**
     * Store a new preference in the database.
     *
     * This method creates a new preference using the provided name from the request. 
     * Authorization is checked before proceeding with the creation. If the user does not have 
     * permission to create a new preference, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * Upon successful creation, the newly created preference is returned as a JSON response with a 201 status code.
     *
     * @param PreferenceStoreRequest $request The request object containing the validation rules for storing a preference.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 201 Created status,
     *                                       containing the newly created preference item wrapped by the PreferenceResource,
     *                                       a 403 Forbidden status if the user is not authorized to create a preference,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function store(PreferenceStoreRequest $request)
    {
        $this->authorize('create', Preference::class);
    
        $preference = Preference::create([
            'name' => $request->name,
            'manip_name' => $request->manipName,
            'user_id' => $request->userId ? $request->userId : auth()->user()->id,
            'location_id' => $request->locationId,
        ]);

        $preference->equipment()->attach($request->equipmentIds);

        $preference->team()->attach($request->teamIds);
    
        return response()->json(PreferenceResource::make($preference), 201);
    }

    /**
     * Display the specified preference.
     *
     * This method retrieves and displays the details of the specified preference. 
     * Authorization is checked to ensure the user has permission to view the preference.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * 
     * @param Preference $preference The preference model instance to be displayed.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified preference wrapped by the PreferenceResource, or a 403 
     *                                       Forbidden status if the user is not authorized to view the preference.
     */
    public function show(Preference $preference)
    {
        $this->authorize('view', $preference);

        return response()->json(PreferenceResource::make($preference));
    }

    /**
     * Update the specified preference.
     *
     * This method updates the details of the specified preference using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update preferences.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * 
     * @param PreferenceStoreRequest $request The request object containing the validation rules for updating a preference.
     * @param Preference $preference The preference model instance to be updated.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update,
     *                                       a 403 Forbidden status if the user is not authorized to update preferences,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(PreferenceStoreRequest $request, Preference $preference)
    {
        $this->authorize('update', $preference);

        $preference->update([
            'name' => $request->name,
            'manip_name' => $request->manipName,
            'user_id' => $request->userId ? $request->userId : auth()->user()->id,
            'location_id' => $request->locationId,
        ]);

        $preference->equipment()->sync($request->equipmentIds);

        $preference->team()->sync($request->teamIds);

        return response()->json(PreferenceResource::make($preference), 200);
    }

    /**
     * Delete the specified preference.
     *
     * This method deletes the specified preference from the database. 
     * Authorization is checked to ensure the user has permission to delete preferences.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param Preference $preference The preference model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete preferences.
     */
    public function destroy(Preference $preference)
    {
        $this->authorize('delete', $preference);

        $preference->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
