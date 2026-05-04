<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);



    Route::middleware('auth')->group(function () {
    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::post('/todos/{id}/complete', [TodoController::class, 'complete']);
    Route::post('/todos/{id}/delete', [TodoController::class, 'delete']);
    Route::post('/todos/{id}/update', [TodoController::class, 'update']);
    Route::post('/todos/{id}/status', [TodoController::class, 'updateStatus']);

});
Route::get('/', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});


Route::get('/login', function () {
    return view('login');
})->name('login');



