<?php
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DonationController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {

    if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')) {
       
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])
    ->can('access', User::class)
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('users', UserController::class);

    Route::resource('activities', ActivityController::class);

    // Modified posts routes to only allow viewing and deleting
    Route::resource('posts', PostController::class)->only(['index', 'show', 'destroy']);
    Route::get('posts/{post}/comments', [PostController::class, 'comments'])->name('posts.comments');
    Route::delete('comments/{id}', [PostController::class, 'deleteComment'])->name('posts.comments.delete');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');


    // Comments routes with only view and delete functionality
    Route::resource('comments', CommentController::class)->only(['index', 'show', 'destroy']);
 
    Route::resource('donations', DonationController::class)->only(['index', 'show']);
    Route::get('donation-statistics', [DonationController::class, 'statistics'])->name('donations.statistics');

})
->can('access', User::class);
require __DIR__ . '/auth.php';


Route::post('/donate', [ActivityController::class, 'donate'])->name('donate')->middleware('auth');

Route::get('/activities/{categoryId?}', [ActivityController::class, 'index'])->name('activities.index');
Route::post('/join-activity/{activityId}', [ActivityController::class, 'joinActivity'])->name('join.activity')->middleware('auth');
Route::post('/activities/{activityId}/leave', [ActivityController::class, 'leaveActivity'])->name('leave.activity')->middleware('auth');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/volunteers', [AboutController::class, 'getVolunteers'])->name('volunteers.get');



require __DIR__ . '/auth.php';
