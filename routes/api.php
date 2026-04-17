<?php

use App\Http\Controllers\ParcellController;
use Illuminate\Http\Request;
use App\Http\Controllers\ResidentController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post("/residents",[ResidentController::class,"store"]);
Route::get("/residents", [ResidentController::class, "index"]);
Route::get("/residents/{id}", [ResidentController::class, "show"]);
Route::delete("/residents/{id}",[ResidentController::class,"destroy"]);

Route::post("/parcels", [ParcellController::class, "store"]);
Route::get("/parcels", [ParcellController::class, "index"]);
Route::delete("/parcels/{id}",[ParcellController::class,'destroy']);