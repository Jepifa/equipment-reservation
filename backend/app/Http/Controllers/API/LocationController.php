<?php

namespace App\Http\Controllers\API;

use App\Models\Location;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Resources\LocationResource;
use Illuminate\Http\Response;

/**
 * Class LocationController
 *
 * This class represents a controller for managing locations via API endpoints. It includes methods
 * for retrieving, storing, updating, and deleting locations. Each method contains authorization checks
 * and validation rules for handling requests related to locations.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class LocationController extends Controller
{
    /**
     * Retrieve a list of all locations in JSON format.
     * 
     * This method fetches all locations from the database, ordered alphabetically by name. Before fetching the data,
     * it checks whether the user has the authorization to view any location. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of locations, 
     *                                       each wrapped by the LocationResource, or a 403 Forbidden status if not authorized.
     */
    public function index()
    {
        $this->authorize('viewAny', Location::class);
            
        $location = LocationResource::collection(Location::orderBy('name', 'asc')->get());
        
        return response()->json($location);
    }

    /**
     * Store a new location in the database.
     *
     * This method creates a new location using the provided name from the request. 
     * Authorization is checked before proceeding with the creation. If the user does not have 
     * permission to create a new location, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * Upon successful creation, the newly created location is returned as a JSON response with a 201 status code.
     *
     * @param LocationStoreRequest $request The request object containing the validation rules for storing a location.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 201 Created status,
     *                                       containing the newly created location item wrapped by the LocationResource,
     *                                       a 403 Forbidden status if the user is not authorized to create a location,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function store(LocationStoreRequest $request)
    {
        $this->authorize('create', Location::class);
    
        $location = Location::create([
            'name' => $request->name,
            'site_id' => $request->siteId
        ]);
    
        return response()->json(LocationResource::make($location), 201);
    }

    /**
     * Display the specified location.
     *
     * This method retrieves and displays the details of the specified location. 
     * Authorization is checked to ensure the user has permission to view the location.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * 
     * @param Location $location The location model instance to be displayed.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified location wrapped by the LocationResource, or a 403 
     *                                       Forbidden status if the user is not authorized to view the location.
     */
    public function show(Location $location)
    {
        $this->authorize('view', $location);

        return response()->json(LocationResource::make($location));
    }

    /**
     * Update the specified location.
     *
     * This method updates the details of the specified location using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update locations.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * 
     * @param LocationStoreRequest $request The request object containing the validation rules for updating a location.
     * @param Location $location The location model instance to be updated.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update,
     *                                       a 403 Forbidden status if the user is not authorized to update locations,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(LocationStoreRequest $request, Location $location)
    {
        $this->authorize('update', Location::class);

        // Update equipment
        $location->update([
            "name" => $request->name,
            "site_id" => $request->siteId,
        ]);

        return response()->json();
    }

    /**
     * Delete the specified location.
     *
     * This method deletes the specified location from the database. 
     * Authorization is checked to ensure the user has permission to delete locations.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param Location $location The location model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete locations.
     */
    public function destroy(Location $location)
    {
        $this->authorize('delete', Location::class);

        $location->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
