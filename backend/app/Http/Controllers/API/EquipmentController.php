<?php

namespace App\Http\Controllers\API;

use App\Models\Equipment;
use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentStoreRequest;
use App\Http\Resources\EquipmentResource;
use Illuminate\Http\Response;

/**
 * Class EquipmentController
 *
 * This class represents a controller for managing equipment via API endpoints. It includes methods
 * for retrieving, storing, updating, and deleting equipment. Each method contains authorization checks
 * and validation rules for handling requests related to equipment.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentController extends Controller
{
    /**
     * Retrieve a list of all equipment items.
     *
     * This method fetches all equipment items from the database, ordered alphabetically by name. 
     * It verifies whether the user has the authorization to view any equipment items before proceeding 
     * with the retrieval. If the user is not authorized, a JSON response with a 403 Forbidden status 
     * is returned. Upon successful retrieval, a JSON response with a 200 OK status is returned, 
     * containing the list of equipment items wrapped by the EquipmentResource.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status, 
     *                                       containing the list of equipment items, 
     *                                       each wrapped by the EquipmentResource, or a 403 Forbidden status 
     *                                       if the user is not authorized to view equipment items.
     */
    public function index()
    {
        $this->authorize('viewAny', Equipment::class);
            
        $equipment = EquipmentResource::collection(Equipment::orderBy('name', 'asc')->get());
        
        return response()->json($equipment);
    }

    /**
     * Store a new equipment item in the database.
     *
     * This method creates a new equipment item using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to create equipment items.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned. 
     * Upon successful creation, a JSON response with a 201 Created status is returned, 
     * containing the newly created equipment item wrapped by the EquipmentResource.
     *
     * @param EquipmentStoreRequest $request The request object containing the validation rules for storing an equipment item.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 201 Created status, 
     *                                       containing the newly created equipment item wrapped by the EquipmentResource,
     *                                       a 403 Forbidden status if the user is not authorized to create equipment items,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function store(EquipmentStoreRequest $request)
    {
        $this->authorize('create', Equipment::class);
    
        $equipment = Equipment::create([
            'name' => $request->name,
            'operational' => $request->operational,
            'equipment_group_id' => $request->equipmentGroupId
        ]);
    
        return response()->json(EquipmentResource::make($equipment), 201);
    }

    /**
     * Display the specified equipment item.
     *
     * This method retrieves and displays the details of the specified equipment item. 
     * Authorization is checked to ensure the user has permission to view the equipment item.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned. 
     * Upon successful retrieval, a JSON response with a 200 OK status is returned, 
     * containing the details of the specified equipment item wrapped by the EquipmentResource.
     *
     * @param Equipment $equipment The equipment model instance to be displayed.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status, 
     *                                       containing the details of the specified equipment item wrapped by the EquipmentResource,
     *                                       or a 403 Forbidden status if the user is not authorized to view the equipment item.
     */
    public function show(Equipment $equipment)
    {
        $this->authorize('view', $equipment);

        return response()->json(EquipmentResource::make($equipment));
    }

    /**
     * Update the specified equipment item.
     *
     * This method updates the details of the specified equipment item using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update equipment items.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned. 
     * Upon successful update, an empty JSON response with a 200 OK status is returned.
     *
     * @param EquipmentStoreRequest $request The request object containing the validation rules for updating an equipment item.
     * @param Equipment $equipment The equipment model instance to be updated.
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update, 
     *                                       a 403 Forbidden status if the user is not authorized to update equipment items,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(EquipmentStoreRequest $request, Equipment $equipment)
    {
        $this->authorize('update', Equipment::class);

        $equipment->update([
            "name" => $request->name,
            "equipment_group_id" => $request->equipmentGroupId,
            "operational" => $request->operational
        ]);

        return response()->json();
    }

    /**
     * Delete the specified equipment item.
     *
     * This method deletes the specified equipment item from the database. 
     * Authorization is checked to ensure the user has permission to delete equipment items.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param Equipment $equipment The equipment model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete equipment items.
     */
    public function destroy(Equipment $equipment)
    {
        $this->authorize('delete', Equipment::class);

        $equipment->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
