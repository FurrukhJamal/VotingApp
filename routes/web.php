<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Illuminate\Http\Request as HttpRequest;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get("/", function () {
//     return Inertia::render("HomePage", []);
// })->name("home");

/* Routes For My Ideas */

// Route::get("/", [IdeaController::class, "index"])
//     ->middleware("auth")
//     ->where([
//         "user" => "true",
//         "category" => "[0-9]+",

//     ]);



Route::get("/", [IdeaController::class, "index"])->name("idea.index");
Route::post("/", [IdeaController::class, "store"])->middleware(["auth", "verified"])->name("idea.store");

// Route::inertia("idea", "IdeaPage", []);

Route::get("ideas/{idea:slug}", [IdeaController::class, "show"])->name("idea.show");

/* status filter links */
Route::get("/statusfilter/all", [IdeaController::class, "index"])->name("status.all");
// Route::get("/statusfilter/open/{category?}", [IdeaController::class, "statusFilterOpen"])->name("status.open");
// Route::get("/statusfilter/considering/{category?}", [IdeaController::class, "statusFilterConsidering"])->name("status.considering");
// Route::get("/statusfilter/inprogress/{category?}", [IdeaController::class, "statusFilterInProgress"])->name("status.inProgress");
// Route::get("/statusfilter/implemented/{category?}", [IdeaController::class, "statusFilterImplemented"])->name("status.implemented");
// Route::get("/statusfilter/closed/{category?}", [IdeaController::class, "statusFilterClosed"])->name("status.closed");

Route::get("/statusfilter/open/{filter?}", [IdeaController::class, "statusFilterOpen"])
    ->where(["filter" => "category|otherfilters"])
    ->name("status.open");

Route::get("/statusfilter/considering/{filter?}", [IdeaController::class, "statusFilterConsidering"])
    ->where(["filter" => "category|otherfilters"])
    ->name("status.considering");

Route::get("/statusfilter/inprogress/{filter?}", [IdeaController::class, "statusFilterInProgress"])
    ->where(["filter" => "category|otherfilters"])
    ->name("status.inProgress");

Route::get("/statusfilter/implemented/{filter?}", [IdeaController::class, "statusFilterImplemented"])
    ->where(["filter" => "category|otherfilters"])
    ->name("status.implemented");

Route::get("/statusfilter/closed/{filter?}", [IdeaController::class, "statusFilterClosed"])
    ->where(["filter" => "category|otherfilters"])
    ->name("status.closed");

/* END status filter links */


//Search
Route::get("/search", [IdeaController::class, "search"])->name("search");


Route::patch("/setstatus", [IdeaController::class, "update"])->middleware("auth")->name("idea.update.status");


//Updating Idea
Route::post("/updateidea", [IdeaController::class, "update"])->middleware(["auth"])->name("idea.update");

//Delete Idea
Route::post("/deleteidea/{idea}", [IdeaController::class, "destroy"])->middleware("auth")->name("idea.destroy");

//Spams
Route::get("/getspam", [IdeaController::class, "getSpam"])->middleware("auth")->name("idea.spam");
Route::post("/voteasspam", [IdeaController::class, "voteAsSpam"])->middleware("auth")->name("idea.voteSpam");
Route::post("/markasnotspam", [IdeaController::class, "markAsNotSpam"])->middleware("auth")->name("idea.markNotAsSpam");

//Comments
Route::post("/addcomment", [CommentController::class, "store"])->middleware("auth")->name("comment.store");
Route::post("/updatecomment", [CommentController::class, "update"])->middleware("auth")->name("comment.update");
//Delete Comment
Route::post("/deletecomment/{comment}", [CommentController::class, "destroy"])->middleware("auth")->name("comment.destroy");

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
