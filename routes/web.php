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
use App\Http\Controllers\TestController;


Route::middleware('auth')->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('course.index');
    Route::get('/course/create', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');
    Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
    Route::get('/course/{course}/edit', [CourseController::class, 'edit'])->name('course.edit');
    Route::patch('/course/{course}', [CourseController::class, 'update'])->name('course.update');
    Route::delete('/course/{course}', [CourseController::class, 'destroy'])->name('course.destroy');

    Route::get('/courses/{course}/words/{word}/edit', [WordController::class, 'edit'])->name('words.edit');
    Route::patch('/courses/{course}/words/{word}', [WordController::class, 'update'])->name('words.update');
    Route::delete('/courses/{course}/words/{word}', [WordController::class, 'destroy'])->name('words.destroy');
    Route::post('/courses/{course}/words', [WordController::class, 'store'])->name('words.store');



    Route::resource('tests', TestController::class);
    Route::get('/tests', [TestController::class, 'index'])->name('tests.index');
    Route::get('/tests/{test}', [TestController::class, 'show'])->name('tests.show');
    Route::get('/tests/create', [TestController::class, 'create'])->name('tests.create');
    Route::post('/tests', [TestController::class, 'store'])->name('tests.store');
    Route::get('/tests/{test}/edit', [TestController::class, 'edit'])->name('tests.edit');
    Route::put('/tests/{test}', [TestController::class, 'update'])->name('tests.update');
    Route::delete('/tests/{test}', [TestController::class, 'destroy'])->name('tests.destroy');
});
