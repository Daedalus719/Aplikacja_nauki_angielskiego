<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CourseWordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IrregularVerbController;
use App\Http\Controllers\SentenceRuleController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('dashboard');


Route::get('/dictionary', [WordController::class, 'dictionary'])->name('dictionary');
Route::match(['get'], '/dictionary', [WordController::class, 'dictionary'])->name('dictionary');
Route::get('/words/load-more', [WordController::class, 'loadMore'])->name('words.load-more');


Route::get('/courses', [CourseController::class, 'index'])->name('course.index');
Route::get('/course/{course}', [CourseWordController::class, 'show'])->name('course-words.show');
Route::get('/tests', [TestController::class, 'index'])->name('tests.index');
Route::get('/tests/{test}', [TestController::class, 'show'])->name('tests.show');

Route::get('/irregular-verbs', [IrregularVerbController::class, 'index'])->name('irregular-verbs.index');
Route::get('/irregular-verbs/tasks', [IrregularVerbController::class, 'showTaskPage'])->name('irregular-verbs.tasks');
Route::get('/irregular-verbs/random', [IrregularVerbController::class, 'getRandomVerbs']);

Route::get('/sentence-rules', [SentenceRuleController::class, 'index'])->name('sentence-rules.index');
Route::get('/sentence-rules/{section}', [SentenceRuleController::class, 'show'])->name('sentence-rules.show');







Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/search-words', [WordController::class, 'search'])->name('words.search');
    Route::get('/words/{id}', [WordController::class, 'show'])->name('words.show');
    Route::get('/words', [WordController::class, 'index'])->name('words.index');
});

Route::middleware(['auth', 'Admin'])->group(function () {
    Route::get('/words/{word}/edit', [WordController::class, 'edit'])->name('words.edit');
    Route::put('/words/{word}', [WordController::class, 'update'])->name('words.update');
    Route::delete('/words/{word}', [WordController::class, 'destroy'])->name('words.destroy');

    Route::match(['post'], '/dictionary', [WordController::class, 'dictionary'])->name('dictionary');

    Route::get('/courses/create', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');
    Route::post('/course/{course}', [CourseWordController::class, 'store'])->name('course-words.store');
    Route::get('/course/{course}/edit', [CourseController::class, 'edit'])->name('course.edit');
    Route::patch('/course/{course}', [CourseController::class, 'update'])->name('course.update');
    Route::delete('/course/{course}', [CourseController::class, 'destroy'])->name('course.destroy');

    Route::get('/search-word', [CourseWordController::class, 'searchWord'])->name('course-words.search');

    Route::get('/tests/create', [TestController::class, 'create'])->name('tests.create');
    Route::post('/tests', [TestController::class, 'store'])->name('tests.store');
    Route::get('/tests/{test}/edit', [TestController::class, 'edit'])->name('tests.edit');
    Route::put('/tests/{test}', [TestController::class, 'update'])->name('tests.update');
    Route::delete('/tests/{test}', [TestController::class, 'destroy'])->name('tests.destroy');

    Route::post('/irregular-verbs', [IrregularVerbController::class, 'store'])->name('irregular-verbs.store');
    Route::put('/irregular-verbs/{id}', [IrregularVerbController::class, 'update'])->name('irregular-verbs.update');
    Route::delete('/irregular-verbs/{id}', [IrregularVerbController::class, 'destroy'])->name('irregular-verbs.destroy');

    Route::post('/sentence-rules/store-section', [SentenceRuleController::class, 'storeSection'])->name('sentence-rules.store-section');
    Route::get('/sentence-rules/addPage', [SentenceRuleController::class, 'addPage'])->name('sentence-rules.addPage');
    Route::post('/sentence-rules/store-rule', [SentenceRuleController::class, 'storeRule'])->name('sentence-rules.store-rule');

});


Route::middleware(['auth', 'Admin'])->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'index'])->name('admin.panel');
    Route::post('/admin/user/update/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/admin/user/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
});



require __DIR__ . '/auth.php';
