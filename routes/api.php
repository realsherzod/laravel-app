<?php

use App\Http\Controllers\Api\DocumentController;
use Illuminate\Support\Facades\Route;


Route::prefix("v1")->group(function () {
    Route::get("documents", [DocumentController::class, 'documents']);
    Route::get("documents/{id}", [DocumentController::class, 'getDocumentById']);
    Route::post("documents/create", [DocumentController::class, 'create']);
});