<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\IntercityReportController;
use App\Http\Controllers\WaterTrasportReportController;

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


Route::get('/post',[PostController::class,'index'] );
Route::post('/post',[PostController::class,'store'] );
Route::delete('/post/{postId}',[PostController::class,'destroy'] );




Route::get('/intercity',[IntercityReportController::class,'index'] );
Route::get('/water-transport',[WaterTrasportReportController::class,'index'] );
Route::get('/water/companies',[WaterTrasportReportController::class,'companies'] );

Route::prefix('water')->group(function () {
    Route::get('/companies', [WaterTrasportReportController::class,'companies'] );
});