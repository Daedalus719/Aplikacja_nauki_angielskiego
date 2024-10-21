<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CourseWordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IrregularVerbController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SentenceController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('dashboard');


Route::get('/dictionary', [WordController::class, 'dictionary'])->name('dictionary');
Route::match(['get'], '/dictionary', [WordController::class, 'dictionary'])->name('dictionary');
Route::get('/words/load-more', [WordController::class, 'loadMoreWords']);
Route::get('/suggestions', [WordController::class, 'getSuggestions'])->name('suggestions');



Route::get('/courses', [CourseController::class, 'index'])->name('course.index');
Route::get('/course/{course}', [CourseWordController::class, 'show'])->name('course-words.show');
Route::get('/tests', [TestController::class, 'index'])->name('tests.index');
Route::get('/tests/{test}', [TestController::class, 'show'])->name('tests.show');

Route::get('/irregular-verbs', [IrregularVerbController::class, 'index'])->name('irregular-verbs.index');
Route::get('/irregular-verbs/tasks', [IrregularVerbController::class, 'showTaskPage'])->name('irregular-verbs.tasks');
Route::get('/irregular-verbs/random', [IrregularVerbController::class, 'getRandomVerbs']);

Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/translation', [GameController::class, 'translationGame'])->name('games.translation');
Route::get('/games/random-words', [GameController::class, 'getRandomWords'])->name('games.randomWords');
Route::get('/games/scrabble', [GameController::class, 'scrabble'])->name('games.scrabble');
Route::post('/scrabble-words', [GameController::class, 'fetchScrabbleWords'])->name('scrabble.words');

Route::get('/flashcards', [GameController::class, 'flashcards'])->name('games.flashcards');
Route::get('/flashcards-test', [GameController::class, 'flashcardsTest'])->name('games.flashcards-test');
Route::post('/check-answers', [GameController::class, 'checkAnswers']);

Route::resource('sections', SectionController::class);
Route::get('sections/tasks', [SectionController::class, 'tasks'])->name('sections.tasks');


Route::get('/sentence_game', [SentenceController::class, 'index'])->name('sentence_game.index');
Route::get('/sentence_game/{id}', [SentenceController::class, 'show'])->name('sentence_game.show');











Route::prefix('tasks/{section_id}')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});





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
    Route::put('/words/{id}', [WordController::class, 'updateWord']);
    Route::delete('/words/{id}', [WordController::class, 'deleteWord']);


    Route::match(['post'], '/dictionary', [WordController::class, 'dictionary'])->name('dictionary');

    Route::get('/courses/create', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');
    Route::post('/course/{course}', [CourseWordController::class, 'store'])->name('course-words.store');
    Route::get('/course/{course}/edit', [CourseController::class, 'edit'])->name('course.edit');
    Route::patch('/course/{course}', [CourseController::class, 'update'])->name('course.update');
    Route::delete('/course/{course}', [CourseController::class, 'destroy'])->name('course.destroy');

    Route::delete('/courses/{course}/words/{word}', [CourseWordController::class, 'destroy'])
    ->name('course-words.destroy');


    Route::get('/search-word', [CourseWordController::class, 'searchWord'])->name('course-words.search');

    Route::get('/tests/create', [TestController::class, 'create'])->name('tests.create');
    Route::post('/tests', [TestController::class, 'store'])->name('tests.store');
    Route::get('/tests/{test}/edit', [TestController::class, 'edit'])->name('tests.edit');
    Route::put('/tests/{test}', [TestController::class, 'update'])->name('tests.update');
    Route::delete('/tests/{test}', [TestController::class, 'destroy'])->name('tests.destroy');

    Route::post('/irregular-verbs', [IrregularVerbController::class, 'store'])->name('irregular-verbs.store');
    Route::put('/irregular-verbs/{id}', [IrregularVerbController::class, 'update'])->name('irregular-verbs.update');
    Route::delete('/irregular-verbs/{id}', [IrregularVerbController::class, 'destroy'])->name('irregular-verbs.destroy');

    Route::post('sections/{section}/add-rule', [SectionController::class, 'addRule'])->name('sections.addRule');
    Route::patch('/rules/{id}', [SectionController::class, 'updateRule'])->name('rules.update');
    Route::delete('/rules/{id}', [SectionController::class, 'deleteRule'])->name('rules.delete');

    Route::post('/sentence_game/{section_id}/add-sentence', [SentenceController::class, 'addSentence'])->name('sentence_game.add-sentence');
    Route::get('/sentence_game/{section}/sentences', [SentenceController::class, 'allSentences'])->name('sentence_game.all-sentences');
    Route::post('/sentence_game/{sentence}/update', [SentenceController::class, 'updateSentence']);
    Route::delete('/sentence_game/{sentence}/delete', [SentenceController::class, 'deleteSentence']);
});


Route::middleware(['auth', 'Admin'])->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'index'])->name('admin.panel');
    Route::post('/admin/user/update/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/admin/user/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
});



require __DIR__ . '/auth.php';
