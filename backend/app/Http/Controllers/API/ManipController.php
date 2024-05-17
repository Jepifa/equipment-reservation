<?php

namespace App\Http\Controllers\API;

use App\Models\Manip;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManipStoreRequest;
use App\Http\Resources\ManipResource;
use App\Rules\UniqueEquipmentForManip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * Class ManipController
 *
 * This class represents a controller for managing manipulations via API endpoints. It includes methods
 * for retrieving, storing, updating, and deleting manipulations. Each method contains authorization checks
 * and validation rules for handling requests related to manipulations.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class ManipController extends Controller
{
    /**
     * Retrieve a list of all manipulations in JSON format.
     * 
     * This method fetches all manipulations from the database, ordered by date. Before fetching the data,
     * it checks whether the user has the authorization to view any manipulation. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the list of manipulations, 
     *                                       each wrapped by the ManipResource, or a 403 Forbidden status if not authorized.
     */
    public function index()
    {
        $this->authorize('viewAny', Manip::class);
            
        $manips = ManipResource::collection(Manip::orderBy('begin_date', 'asc')->get());
        
        return response()->json($manips);
    }

    /**
     * Retrieve a list of manipulations associated with the authenticated user.
     *
     * This method fetches all manipulations associated with the authenticated user from the database, 
     * ordered by date. It verifies whether the authenticated user has the authorization 
     * to view any manipulations before proceeding with the retrieval. If the user is not authorized, 
     * a JSON response with a 403 Forbidden status is returned. Upon successful retrieval, a JSON response 
     * with a 200 OK status is returned, containing the list of manipulations wrapped by the ManipResource.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status, 
     *                                       containing the list of manipulations associated with the authenticated user,
     *                                       each wrapped by the ManipResource, or a 403 Forbidden status 
     *                                       if the user is not authorized to view manipulations.
     */
    public function indexByUser()
    {
        $this->authorize('viewAnyByUser', Manip::class);
            
        $manips = ManipResource::collection(auth()->user()->manips()->orderBy('begin_date', 'asc')->get());
        
        return response()->json($manips);
    }

    /**
     * Create a manipulation within a recurrence period.
     *
     * This method creates a manipulation with the provided data within a specified recurrence period. 
     * It validates the request data to ensure that the 'equipmentIds' array is required, non-empty, 
     * and contains unique equipment items for the given recurrence period. Upon successful validation, 
     * a manipulation record is created in the database with the provided data, including the name, 
     * user ID, begin date, end date, and location ID. The equipment and team associations are also 
     * established for the created manipulation.
     * 
     * @param Request $request The request object containing the data for creating the manipulation.
     * @param Carbon $beginDate The beginning date of the recurrence period.
     * @param Carbon $endDate The end date of the recurrence period.
     * 
     * @return void
     */
    private function createManipInRecurrence(Request $request, Carbon $beginDate, Carbon $endDate) {
        $this->validate($request, [
            'equipmentIds' => ['required', 'array', 'min:1', new UniqueEquipmentForManip($beginDate, $endDate)],
        ]);
        $manip = Manip::create([
            'name' => $request->name,
            'user_id' => $request->userId ? $request->userId : auth()->user()->id,
            'begin_date' => $beginDate,
            'end_date' => $endDate,
            'location_id' => $request->locationId,
        ]);

        $manip->equipment()->attach($request->equipmentIds);

        $manip->team()->attach($request->teamIds);
    }

    /**
     * Store a new manipulation or a set of manipulations.
     *
     * This method stores a new manipulation or a set of manipulations based on the provided data from the request. 
     * Authorization is checked to ensure the user has permission to create manipulations. 
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned. 
     * Upon successful creation of a single manipulation or a set of manipulations, a JSON response 
     * with a 201 Created status is returned, indicating that the manipulations have been created successfully.
     * 
     * @param ManipStoreRequest $request The request object containing the data for creating the manipulation(s).
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 201 Created status, indicating 
     *                                       that the manipulations have been created successfully, a 403 Forbidden 
     *                                       status if the user is not authorized to create manipulations,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function store(ManipStoreRequest $request)
    {
        $this->authorize('create', Manip::class);

        // If no recurrence is selected, create a single manipulation
        if (!$request->selectedRecurrence) {
            $manip = Manip::create([
                'name' => $request->name,
                'user_id' => $request->userId ? $request->userId : auth()->user()->id,
                'begin_date' => Carbon::parse($request->beginDate),
                'end_date' => Carbon::parse($request->endDate),
                'location_id' => $request->locationId,
            ]);
    
            $manip->equipment()->attach($request->equipmentIds);
    
            $manip->team()->attach($request->teamIds);

            return response()->json(ManipResource::make($manip), 201);
        }

        // If recurrence is selected, handle the recurrence logic
        else {
            // Validate recurrence-related data
            $this->validate($request, [
                'endDate' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $beginDate = Carbon::parse($request->beginDate)->startOfDay();
                        $endDate = Carbon::parse($value)->startOfDay();
            
                        if (!$beginDate->eq($endDate)) {
                            $fail('The end date must be the same day as the begin date when using recurrence.');
                        }
                    },
                ],
                'endRecurrenceDate' => ['required'],
                'endRecurrenceDateFormatted' => ['required', 'after:beginDate'],
            ]);

            // Parse and prepare recurrence-related dates
            $endRecurrenceDate = Carbon::parse($request->endRecurrenceDateFormatted)->setHour(23)->setMinute(59)->setSecond(59);
            $currentBeginDate = Carbon::parse($request->beginDate);
            $currentEndDate = Carbon::parse($request->endDate);

            // Handle different recurrence types
            if ($request->selectedRecurrence == "weekly") {
                // Weekly recurrence logic
                if (!$request->selectedWeeklyRecurrence) {
                    try {
                        // Loop through each week until the end recurrence date
                        while ($currentBeginDate <= $endRecurrenceDate) {
                            // Create manipulation for each non-weekend day
                            if (!$currentBeginDate->isWeekend()){
                                $this->createManipInRecurrence($request, $currentBeginDate, $currentEndDate);
                            }
                            $currentBeginDate->addWeek();
                            $currentEndDate->addWeek();
                        }
                        return response()->json('Manipulations created successfully', 201);
                    } catch (ValidationException $e) {
                        $errors = $e->validator->errors()->toArray();
                        return response()->json(['errors' => $errors], 422);
                    }
                } else {
                    // Handling recurrence for selected weekly days
                    // Validate selected days for recurrence
                    $this->validate($request, [
                        'multipleDaysRecurrence' => ['required', 'array'],
                    ]);

                    // Ensure at least one day is selected for recurrence
                    if (!in_array(true, $request->multipleDaysRecurrence)) {
                        return response()->json(['errors' => ['multipleDaysRecurrence' => ['At least one day must be chosen']]], 422);
                    }

                    // Prepare begin and end dates for each selected day
                    $beginDates = [];
                    $endDates = [];
    
                    foreach ($request->multipleDaysRecurrence as $day => $selected) {
                        if ($selected) {
                            // Calculate new begin and end dates for each selected day
                            $newBeginDate = $currentBeginDate->copy()->subDay()->next($day);
                            $newBeginDate->setTime($currentBeginDate->hour, $currentBeginDate->minute, $currentBeginDate->second);
                            $beginDates[] = $newBeginDate;
                            $newEndDate = $currentEndDate->copy()->subDay()->next($day);
                            $newEndDate->setTime($currentEndDate->hour, $currentEndDate->minute, $currentEndDate->second);
                            $endDates[] = $newEndDate;
                        }
                    }
    
                    // Sort begin and end dates
                    usort($beginDates, function($a, $b) {
                        return $a->timestamp - $b->timestamp;
                    });
    
                    usort($endDates, function($a, $b) {
                        return $a->timestamp - $b->timestamp;
                    });
    
                    // Create manipulations for each selected day
                    $index = 0;
                    try {
                        while ($beginDates[$index] <= $endRecurrenceDate) {
                            $this->createManipInRecurrence($request, $beginDates[$index], $endDates[$index]);
    
                            $beginDates[$index]->addWeek();
                            $endDates[$index]->addWeek();
                            $index = ($index + 1) % count($beginDates);
                        }
                        return response()->json('Manipulations created successfully', 201);
                    } catch (ValidationException $e) {
                        $errors = $e->validator->errors()->toArray();
                        return response()->json(['errors' => $errors], 422);
                    }
                }
            }
        
            // Handle daily recurrence
            else if ($request->selectedRecurrence == "daily") {
                try {
                    // Loop through each day until the end recurrence date
                    while ($currentBeginDate <= $endRecurrenceDate) {
                        // Create manipulation for each non-weekend day
                        if (!$currentBeginDate->isWeekend()){
                            $this->createManipInRecurrence($request, $currentBeginDate, $currentEndDate);
                        }
                        $currentBeginDate->addDay();
                        $currentEndDate->addDay();
                    }
                    return response()->json('Manipulations created successfully', 201);
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors()->toArray();
                    return response()->json(['errors' => $errors], 422);
                }
            }
        }
    }

    /**
     * Display the specified manipulation.
     *
     * This method retrieves and displays the details of the specified manipulation. 
     * Authorization is checked to ensure the user has permission to view the manipulation.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * 
     * @param Manip $manip The manip model instance to be displayed.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with a 200 OK status containing the details of 
     *                                       the specified manipulation wrapped by the ManipResource, or a 403 
     *                                       Forbidden status if the user is not authorized to view the manipulation.
     */
    public function show(Manip $manip)
    {
        $this->authorize('view', $manip);

        return response()->json(ManipResource::make($manip));
    }

    /**
     * Update the specified manipulation.
     *
     * This method updates the details of the specified manipulation using the provided data from the request. 
     * Authorization is checked to ensure the user has permission to update manipulations.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * If the request data fails validation, a JSON response with a 422 Unprocessable Entity status
     * containing details of the validation errors is returned.
     * 
     * @param ManipStoreRequest $request The request object containing the validation rules for updating a manipulation.
     * @param Manip $manip The manip model instance to be updated.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 200 OK status upon successful update,
     *                                       a 403 Forbidden status if the user is not authorized to update manipulations,
     *                                       or a 422 Unprocessable Entity status if the request data fails validation.
     */
    public function update(ManipStoreRequest $request, Manip $manip)
    {
        $this->authorize('update', $manip);

        $manip->update([
            'name' => $request->name,
            'user_id' => $request->userId ? $request->userId : auth()->user()->id,
            'begin_date' => Carbon::parse($request->beginDate),
            'end_date' => Carbon::parse($request->endDate),
            'location_id' => $request->locationId,
        ]);

        $manip->equipment()->sync($request->equipmentIds);

        $manip->team()->sync($request->teamIds);

        return response()->json(ManipResource::make($manip), 200);
    }

    /**
     * Delete the specified manipulation.
     *
     * This method deletes the specified manipulation from the database. 
     * Authorization is checked to ensure the user has permission to delete manipulations.
     * If the user is not authorized, a JSON response with a 403 Forbidden status is returned.
     * Upon successful deletion, an empty JSON response with a 204 No Content status is returned.
     * 
     * @param Manip $manip The manip model instance to be deleted.
     * 
     * @return \Illuminate\Http\JsonResponse Returns an empty JSON response with a 204 No Content status 
     *                                       upon successful deletion, or a 403 Forbidden status if 
     *                                       the user is not authorized to delete manipulations.
     */
    public function destroy(Manip $manip)
    {
        $this->authorize('delete', $manip);

        $manip->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
