<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


route::get('Admin/dashboard',[HomeController::class,'index'])->
    middleware(['auth','Admin']);




use App\Http\Controllers\CourseController;
use App\Http\Controllers\WordController;

Route::get('/course/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
Route::get('/create', [CourseController::class, 'create'])->name('create');
Route::post('/course', [CourseController::class, 'store'])->name('course.store');

Route::post('/courses/{course}/words', [WordController::class, 'store'])->name('words.store');

