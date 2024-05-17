<?php

namespace App\Http\Controllers\API;

use App\Models\Site;
use App\Http\Controllers\Controller;
use App\Http\Requests\SiteStoreRequest;
use App\Http\Resources\SiteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class SiteController
 *
 * This class represents a controller for managing sites via API endpoints. It includes methods
 * for retrieving, storing, updating, and deleting sites. Each method contains authorization checks
 * and validation rules for handling requests related to sites.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class SiteController extends Controller
{
    /**
     * Retrieve a list of all sites in JSON format.
     * 
     * This method fetches all sites from the database, ordered alphabetically by name. Before fetching the data,
     * it checks whether the user has the authorization to view any site. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of sites, 
     *                                       each wrapped by the SiteResource, or a 403 Forbidden status if not authorized.
     */
    public function index()
    {
        $this->authorize('viewAny', Site::class);
            
        $site = SiteResource::collection(Site::orderBy('name', 'asc')->get());
        
        return response()->json($site);
    }

    /**
     * Store a new site in the database.
     *
     * This method creates a new site using the provided name from the request. 
     * Authorization is checked before proceeding with the creation. If the user does not have 
     * permission to create a new site, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * Upon successful creation, the newly created site is returned as a JSON response with a 201 status code.
     *
     * @param SiteStoreRequest $request The request object containing the validation rules for storing a site.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 201 Created status,
     *                                       containing the newly created site item wrapped by the SiteResource,
     *                                       a 403 Forbidden status if the user is not authorized to create a site,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function store(SiteStoreRequest $request)
    {
        $this->authorize('create', Site::class);
    
        $site = Site::create([
            'name' => $request->name,
        ]);
    
        return response()->json(SiteResource::make($site), 201);
    }

    /**
     * Display the specified site.
     *
     * This method retrieves and displays the details of the specified site. 
     * Authorization is checked to ensure the user has permission to view the site.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * 
     * @param Site $site The site model instance to be displayed.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified site wrapped by the SiteResource, or a 403 
     *                                       Forbidden status if the user is not authorized to view the site.
     */
    public function show(Site $site)
    {
        $this->authorize('view', $site);

        return response()->json(SiteResource::make($site));
    }

    /**
     * Update the specified site.
     *
     * This method updates the details of the specified site using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update sites.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * 
     * @param SiteStoreRequest $request The request object containing the validation rules for updating a site.
     * @param Site $site The site model instance to be updated.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update,
     *                                       a 403 Forbidden status if the user is not authorized to update sites,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(SiteStoreRequest $request, Site $site)
    {
        $this->authorize('update', Site::class);

        // Update equipment
        $site->update([
            "name" => $request->name,
        ]);

        return response()->json();
    }

    /**
     * Delete the specified site.
     *
     * This method deletes the specified site from the database. 
     * Authorization is checked to ensure the user has permission to delete sites.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param Site $site The site model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete sites.
     */
    public function destroy(Site $site)
    {
        $this->authorize('delete', Site::class);

        $site->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
