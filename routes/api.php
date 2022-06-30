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


Route::post('/add',[DomainsController::class,'createDomain']);


// domain management routes

Route::prefix('domains')->group(function(){
            //get all data
    Route::get('',[DomainsController::class,'getAllDomains']);

    //get single data
    Route::get('/{domain}',[DomainsController::class,'getByDomain']);

    //create domain
    Route::post('/add',[DomainsController::class,'createDomain']);

    //delete domain
    Route::delete('/delete/{domain}',[DomainsController::class,'deleteDomain']);

    //update domain
    Route::put('/update/{domain}',[DomainsController::class,'updateDomain']);

    // Reset custom Nameserver keys to the default values for the domain
    Route::delete('reset-domain/{domain}',[DomainsController::class,'resetDomain']);

    // check activity
    Route::get('/is-active/{domain}',[DomainsController::class,'ActivityDomain']);

    // Set a custom record for using CNAME Setup
    Route::put('/cname-setup/{domain}',[DomainsController::class,'cnameSetup']);

    //Reset the custom record of CNAME Setup to the default value
    Route::delete('/reset-cname-setup/{domain}',[DomainsController::class,'resetCnameSetup']);

    // Convert domain setup to cname
    // Cname setup can be used with subdomain
    Route::post('/convert-to-cname/{domain}',[DomainsController::class,'convertToCname']);

    // Check Cname Setup to find whether domain is activated
    Route::get('/check-cname-activity/{domain}',[DomainsController::class,'checkCnameForActivity']);

    // Clone a domain config from another one
    Route::post('/clone-config/{domain}',[DomainsController::class,'cloneConfig']);




});

// domain management routes

