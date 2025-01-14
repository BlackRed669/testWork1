<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\WorkersController;
use Illuminate\Support\Facades\Route;



Route::get('/departments', function () { return view('department-table'); });

Route::get('/departmentEdit',               [DepartmentsController::class, 'getDepartmentEdit'])        ->name('getDepartmentEdit');
Route::get('/getDepartments',               [DepartmentsController::class, 'getDepartments'])           ->name('getDepartments');
Route::post('/setDepartment',               [DepartmentsController::class, 'setDepartment'])            ->name('setDepartment');
Route::delete('/deleteDepartment',          [DepartmentsController::class, 'deleteDepartment'])         ->name('deleteDepartment');
Route::delete('/deleteFromDepartment',      [DepartmentsController::class, 'deleteFromDepartment'])     ->name('deleteFromDepartment');
Route::get('/getWorkersForDepartment',      [DepartmentsController::class, 'getWorkersForDepartment'])  ->name('getWorkersForDepartment');
Route::post('/addWorkerToDepartment',       [DepartmentsController::class, 'addWorkerToDepartment'])    ->name('addWorkerToDepartment');


Route::get('/', function () { return view('worker-table'); });
Route::get('/workers', function () { return view('worker-table'); });
Route::get('/getWorkers',               [WorkersController::class, 'getWorkers'])           ->name('getWorkers');
Route::get('/workerEdit',               [WorkersController::class, 'getWorkerEdit'])        ->name('getWorkerEdit');
Route::post('/setWorker',               [WorkersController::class, 'setWorker'])            ->name('setWorker');
Route::delete('/deleteWorker',          [WorkersController::class, 'deleteWorker'])         ->name('deleteWorker');
Route::get('/getAllDepartments',        [WorkersController::class, 'getAllDepartments'])    ->name('getAllDepartments');

