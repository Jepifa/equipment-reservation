<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\EquipmentController;
use App\Http\Controllers\API\EquipmentGroupController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\ManipController;
use App\Http\Controllers\API\PreferenceController;
use App\Http\Controllers\API\SiteController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/**
 * API Routes
 *
 * This file contains the API routes for the application. These routes are loaded by the RouteServiceProvider
 * within a group assigned to the "api" middleware group. It includes routes for managing users, equipments,
 * equipment groups, categories, locations, sites, manipulations, and preferences.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/user', [UserController::class, 'showCurrentUser']);
});

Route::get("users/other-users", [UserController::class, 'indexOtherUsers']);
Route::put("users/{user}/change-color/{color}", [UserController::class, 'changeColor']);
Route::apiResource("users", UserController::class)->except([
    'store'
]);
Route::apiResource("equipments", EquipmentController::class);
Route::apiResource("equipment-groups", EquipmentGroupController::class);
Route::apiResource("categories", CategoryController::class);
Route::apiResource("locations", LocationController::class);
Route::apiResource("sites", SiteController::class);
Route::get("manips/user", [ManipController::class, 'indexByUser']);
Route::apiResource("manips", ManipController::class);
Route::get("preferences/user", [PreferenceController::class, 'indexByUser']);
Route::apiResource("preferences", PreferenceController::class);