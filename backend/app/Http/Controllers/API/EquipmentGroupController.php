<?php

namespace App\Http\Controllers\API;

use App\Models\EquipmentGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentGroupStoreRequest;
use App\Http\Resources\EquipmentGroupResource;
use Illuminate\Http\Response;

/**
 * Class EquipmentGroupController
 *
 * This class represents a controller for managing equipment groups via API endpoints. It includes methods
 * for retrieving, storing, updating, and deleting equipment groups. Each method contains authorization checks
 * and validation rules for handling requests related to equipment groups.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentGroupController extends Controller
{
    /**
     * Retrieve a list of all equipment groups in JSON format.
     * 
     * This method fetches all equipment groups from the database, ordered alphabetically by name. Before fetching the data,
     * it checks whether the user has the authorization to view any equipment group. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of equipment groups, 
     *                                       each wrapped by the EquipmentGroupResource, or a 403 Forbidden status if not authorized.
     */
    public function index()
    {
        $this->authorize('viewAny', EquipmentGroup::class);
            
        $equipmentGroup = EquipmentGroupResource::collection(EquipmentGroup::orderBy('name', 'asc')->get());
        
        return response()->json($equipmentGroup);
    }

    /**
     * Store a new equipment group in the database.
     *
     * This method creates a new equipment group using the provided name from the request. 
     * Authorization is checked before proceeding with the creation. If the user does not have 
     * permission to create a new equipment group, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * Upon successful creation, the newly created equipment group is returned as a JSON response with a 201 status code.
     *
     * @param EquipmentGroupStoreRequest $request The request object containing the validation rules for storing an equipment group.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 201 Created status,
     *                                       containing the newly created equipment group item wrapped by the EquipmentGroupResource,
     *                                       a 403 Forbidden status if the user is not authorized to create an equipment group,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function store(EquipmentGroupStoreRequest $request)
    {
        $this->authorize('create', EquipmentGroup::class);
    
        $equipmentGroup = EquipmentGroup::create([
            'name' => $request->name,
            'category_id' => $request->categoryId
        ]);
    
        return response()->json(EquipmentGroupResource::make($equipmentGroup), 201);
    }

    /**
     * Display the specified equipment group.
     *
     * This method retrieves and displays the details of the specified equipment group. 
     * Authorization is checked to ensure the user has permission to view the equipment group.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * 
     * @param EquipmentGroup $equipmentGroup The equipment group model instance to be displayed.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified equipment group wrapped by the EquipmentGroupResource, or a 403 
     *                                       Forbidden status if the user is not authorized to view the equipment group.
     */
    public function show(EquipmentGroup $equipmentGroup)
    {
        $this->authorize('view', $equipmentGroup);

        return response()->json(EquipmentGroupResource::make($equipmentGroup));
    }

    /**
     * Update the specified equipment group.
     *
     * This method updates the details of the specified equipment group using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update equipment groups.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * 
     * @param EquipmentGroupStoreRequest $request The request object containing the validation rules for updating an equipment group.
     * @param EquipmentGroup $equipmentGroup The equipment group model instance to be updated.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update,
     *                                       a 403 Forbidden status if the user is not authorized to update equipment groups,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(EquipmentGroupStoreRequest $request, EquipmentGroup $equipmentGroup)
    {
        $this->authorize('update', EquipmentGroup::class);

        $equipmentGroup->update([
            "name" => $request->name,
            "category_id" => $request->categoryId,
        ]);

        return response()->json();
    }

    /**
     * Delete the specified equipment group.
     *
     * This method deletes the specified equipment group from the database. 
     * Authorization is checked to ensure the user has permission to delete equipment groups.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param EquipmentGroup $equipmentGroup The equipment group model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete equipment groups.
     */
    public function destroy(EquipmentGroup $equipmentGroup)
    {
        $this->authorize('delete', EquipmentGroup::class);

        $equipmentGroup->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
