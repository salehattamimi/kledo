<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Route;

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
Route::patch('settings', [ApiController::class, 'patch_settings']); 
Route::post('employees', [ApiController::class, 'store_employees']); 
Route::post('overtimes', [ApiController::class, 'overtimes']); 
Route::get('overtime-pays/calculate', [ApiController::class, 'overtimes_calculate']); 