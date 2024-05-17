<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Response;

/**
 * Class CategoryController
 *
 * This class represents a controller for managing categories via API endpoints. It includes methods
 * for retrieving, storing, updating, and deleting categories. Each method contains authorization checks
 * and validation rules for handling requests related to categories.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class CategoryController extends Controller
{
    /**
     * Retrieve a list of all categories in JSON format.
     * 
     * This method fetches all categories from the database, ordered alphabetically by name. Before fetching the data,
     * it checks whether the user has the authorization to view any category. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of categories, 
     *                                       each wrapped by the CategoryResource, or a 403 Forbidden status if not authorized.
     */
    public function index()
    {
        $this->authorize('viewAny', Category::class);
            
        $category = CategoryResource::collection(Category::orderBy('name', 'asc')->get());
        
        return response()->json($category);
    }

    /**
     * Store a new category in the database.
     *
     * This method creates a new category using the provided name from the request. 
     * Authorization is checked before proceeding with the creation. If the user does not have 
     * permission to create a new category, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * Upon successful creation, the newly created category is returned as a JSON response with a 201 status code.
     *
     * @param CategoryStoreRequest $request The request object containing the validation rules for storing a category.
     * @return \Illuminate\Http\JsonResponse Returns the newly created category as a JSON response with a 201 status code,
     *                                       a 403 Forbidden status if the user is not authorized to create a category,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function store(CategoryStoreRequest $request)
    {
        $this->authorize('create', Category::class);
    
        $category = Category::create([
            'name' => $request->name,
        ]);
    
        return response()->json($category, 201);
    }

    /**
     * Display the specified category.
     *
     * This method retrieves and displays the details of the specified category. 
     * Authorization is checked to ensure the user has permission to view the category.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * 
     * @param Category $category The category model instance to be displayed.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified category wrapped by the CategoryResource, or a 403 
     *                                       Forbidden status if the user is not authorized to view the category.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return response()->json(CategoryResource::make($category));
    }

    /**
     * Update the specified category.
     *
     * This method updates the details of the specified category using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update categories.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * 
     * @param CategoryStoreRequest $request The request object containing the validation rules for updating a category.
     * @param Category $category The category model instance to be updated.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update,
     *                                       a 403 Forbidden status if the user is not authorized to update categories,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(CategoryStoreRequest $request, Category $category)
    {
        $this->authorize('update', Category::class);

        // Update equipment
        $category->update([
            "name" => $request->name,
        ]);

        return response()->json();
    }

    /**
     * Delete the specified category.
     *
     * This method deletes the specified category from the database. 
     * Authorization is checked to ensure the user has permission to delete categories.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param Category $category The category model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete categories.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', Category::class);

        $category->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
