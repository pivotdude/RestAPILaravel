<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LoginController;
use \App\Http\Controllers\RegionController;
use \App\Http\Controllers\OrganizationsController;
use \App\Http\Controllers\ConsultantsController;
use \App\Http\Controllers\CategoriesController;
use \App\Http\Controllers\ProblemsController;
use \App\Http\Controllers\ConsultationsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("auth/login", [LoginController::class, "login"]);
Route::post("auth/register", [LoginController::class, "register"]);
Route::any("auth/user", [LoginController::class, "user"])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);;
Route::post("auth/logout", [LoginController::class, "logout"])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);

Route::get('regions/', [RegionController::class, 'get_all_regions'])->middleware(\App\Http\Middleware\EnsureUserHasRole::class);
Route::post('regions/', [RegionController::class, 'add_region'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Добавление региона
Route::put('regions/{region}', [RegionController::class, 'edit_region'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Изменение региона
Route::delete('regions/{region}', [RegionController::class, 'delete_region'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Удаление региона

Route::get('regions/{region}/organizations', [OrganizationsController::class, 'get_all_organizations'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Все организации
Route::post('regions/{region}/organizations', [OrganizationsController::class, 'add_organization'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Добавление региона
Route::put('regions/{region}/organizations/{organization}', [OrganizationsController::class, 'edit_organization'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Изменение региона
Route::delete('regions/{region}/organizations/{organization}', [OrganizationsController::class, 'delete_organization'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Удаление региона


Route::get('regions/{region}/organizations/{organization}/consultants', [ConsultantsController::class, 'get_all_consultants'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Все организации
Route::post('regions/{region}/organizations/{organization}/consultants', [ConsultantsController::class, 'add_consultants'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Добавление региона
Route::put('regions/{region}/organizations/{organization}/consultants/{consultant}', [ConsultantsController::class, 'edit_consultants'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Изменение региона
Route::delete('regions/{region}/organizations/{organization}/consultants/{consultant}', [ConsultantsController::class, 'delete_consultants'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']); // Удаление региона

Route::get('/categories', [CategoriesController::class, 'get_all_categories'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::post('/categories', [CategoriesController::class, 'create_category'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::put('/categories/{category_id}', [CategoriesController::class, 'edit_category'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::delete('/categories/{category_id}', [CategoriesController::class, 'delete_category'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);

Route::get('/categories/{category_id}/problems', [ProblemsController::class, 'get_all_problems'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::post('/categories/{category_id}/problems', [ProblemsController::class, 'create_problems'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::put('/categories/{category_id}/problems/{problems_id}', [ProblemsController::class, 'edit_problems'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::delete('/categories/{category_id}/problems/{problems_id}', [ProblemsController::class, 'delete_problems'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);

Route::get('/consultations', [ConsultationsController::class, 'get_all_consultations'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::get('/consultations/{consultation_id}', [ConsultationsController::class, 'get_consultations'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::post('/consultations', [ConsultationsController::class, 'create_consultation'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);
Route::patch('/consultations/{consultation_id}', [ConsultationsController::class, 'create_consultation'])->middleware(['auth:sanctum', 'ability:check-status,place-orders']);

//Tasks 1) Change status
//      2) /problems
//      3) Roles
