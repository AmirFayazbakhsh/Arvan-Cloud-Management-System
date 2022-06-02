<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Domains\DomainsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//get all data
Route::get('domains',[DomainsController::class,'getAllDomains']);

//get single data
Route::get('domains/{domain}',[DomainsController::class,'getByDomain']);

//create domain
Route::post('domains/add',[DomainsController::class,'createDomain']);

//delete domain
Route::delete('domains/delete/{domain}',[DomainsController::class,'deleteDomain']);

//update domain
Route::put('domains/update/{domain}',[DomainsController::class,'updateDomain']);

// Reset custom Nameserver keys to the default values for the domain
Route::delete('reset-domain/{domain}',[DomainsController::class,'resetDomain']);




