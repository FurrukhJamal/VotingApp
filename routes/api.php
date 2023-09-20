<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("/vote", [VoteController::class, "store"])->middleware(["api"])->name("vote.store");
// Route::post("/vote", function (Request $request) {
//     // dd($request);
//     return ["ss" => $request->json()->all()];
// })->name("vote.store");

//deleteing a vote
Route::post("/deletevote", [VoteController::class, "destroy"])->middleware(["api"])->name("vote.destroy");
