<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('v1/auth/login', [AuthController::class, 'login']);

Route::post('v1/auth/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);

Route::post('v1/forms', [FormController::class, 'create_form'])->middleware(['auth:sanctum']);

Route::get('v1/forms', [FormController::class, 'all_form'])->middleware(['auth:sanctum']);

Route::get('v1/forms/{slug}', [FormController::class, 'detail_form'])->middleware(['auth:sanctum']);

Route::post('v1/forms/{slug}/questions', [QuestionController::class, 'create_question'])->middleware(['auth:sanctum']);

Route::delete('/v1/forms/{slug}/questions/{id}', [QuestionController::class, 'delete_question'])->middleware(['auth:sanctum']);

Route::post('v1/forms/{slug}/responses', [ResponseController::class, 'submit_response'])->middleware(['auth:sanctum']);

Route::get('v1/forms/{slug}/responses', [ResponseController::class, 'all_response'])->middleware(['auth:sanctum']);
