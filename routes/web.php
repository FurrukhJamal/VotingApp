<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get("/", [IdeaController::class, "index"])->name("idea.index");
Route::post("/", [IdeaController::class, "store"])->middleware(["auth", "verified"])->name("idea.store");

// Route::inertia("idea", "IdeaPage", []);

Route::get("ideas/{idea:slug}", [IdeaController::class, "show"])->name("idea.show");

/* status filter links */
Route::get("/statusfilter/all", [IdeaController::class, "index"])->name("status.all");
Route::get("/statusfilter/open", [IdeaController::class, "statusFilterOpen"])->name("status.open");
Route::get("/statusfilter/considering", [IdeaController::class, "statusFilterConsidering"])->name("status.considering");
Route::get("/statusfilter/inprogress", [IdeaController::class, "statusFilterInProgress"])->name("status.inProgress");
Route::get("/statusfilter/implemented", [IdeaController::class, "statusFilterImplemented"])->name("status.implemented");
Route::get("/statusfilter/closed", [IdeaController::class, "statusFilterClosed"])->name("status.closed");
/* END status filter links */


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
