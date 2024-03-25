<?php

use App\Http\Controllers\RegisterLoadsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UploadVideo;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('auth/register', [UsersController::class, 'create']);
Route::post('auth/login', [UsersController::class, 'login']);
Route::post('auth/logout', [UsersController::class, 'logout']);
Route::middleware(['auth:sanctum', 'VerifyAppVersion'])->group(function (){

    Route::get('getRegisterData', [RegisterLoadsController::class, 'getRegisterData']);


    Route::post('saveRegisterDate', [RegisterLoadsController::class, 'saveRegisterDate']);
    Route::post('getVideoByDate', [RegisterLoadsController::class, 'getVideoByDate']);
    Route::post('discartVideo', [RegisterLoadsController::class, 'discartVideo']);
    Route::post('confirmVideo', [RegisterLoadsController::class, 'confirmVideo']);
    Route::post('saveNewRegisters', [RegisterLoadsController::class, 'saveNewRegisters']);


    Route::get('getAllDirectories', [UploadVideo::class, 'getAllDirectories']);
    Route::post('createFolder', [UploadVideo::class, 'createFolder']);
    Route::post('deleteDirectory', [UploadVideo::class, 'deleteDirectory']);
    Route::post('saveFilesIntoDirectory', [UploadVideo::class, 'saveFilesIntoDirectory']);

    //reportes
    Route::post('getReports', [ReportsController::class, 'getReports']);
    Route::post('deleteRegisterByID', [ReportsController::class, 'deleteRegisterByID']);
    Route::post('editRegisterByID', [ReportsController::class, 'editRegisterByID']);

});
