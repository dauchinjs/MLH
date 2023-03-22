<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
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

Route::get('/', [HomeController::class, 'getInfo']);
Route::delete('/delete/course/{courseId}/user/{userId}', [HomeController::class, 'deleteCourse'])->name('deleteCourse');
Route::put('/update/course/{courseId}/user/{userId}/status/{status}', [HomeController::class, 'updateStatus'])->name('updateStatus');
Route::put('/create/course/{courseTitle}/user/{email}/status/{status}', [HomeController::class, 'createCourse'])->name('createCourse');
