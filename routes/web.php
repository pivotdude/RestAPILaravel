<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LoginController;
use \App\Http\Controllers\RegionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::post('/login', function () {
//    return response()->json(['message' => 'Please auth']);
//});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("auth/login", [LoginController::class, "login"]);
Route::post("auth/register", [LoginController::class, "register"]);
//Route::any("auth/user", [LoginController::class, "user"])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);;
Route::post("auth/logout", [LoginController::class, "logout"])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);;

Route::get('regions/', [RegionController::class, 'get_all_regions']); // Все регионы
Route::post('regions/', [RegionController::class, 'add_region']); // Добавление региона
Route::post('regions/{region}', [RegionController::class, 'edit_region']); // Добавление региона
Route::delete('regions/{region}', [RegionController::class, 'delete_region']); // Удаление региона

//Route::
