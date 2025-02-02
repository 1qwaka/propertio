<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tool', 'App\Http\Controllers\ToolController@index')->name('KDTool');

Route::post('/tool/calculate-and-get-density', 'ToolController@CalcDensity');


Route::any('/test', [\App\Http\Controllers\ToolController::class, 'testFunction']);


Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout']);
Route::middleware(\App\Http\Middleware\MyAuthMiddleware::class)
    ->get('/self', [\App\Http\Controllers\UserController::class, 'self']);


Route::middleware(\App\Http\Middleware\MyAuthMiddleware::class)
    ->controller(\App\Http\Controllers\AgentController::class)
    ->prefix('agents')->group(function () {
        Route::post('/register', 'register');
        Route::get('/types', 'types');
//        Route::get('/stats', 'stats');
        Route::get('/self', 'self');
        Route::patch('', 'update');

    });

Route::controller(\App\Http\Controllers\DeveloperController::class)
    ->prefix('developers')->group(function () {
        Route::middleware(\App\Http\Middleware\MyAuthAdminMiddleware::class)->group(function () {
            Route::post('', 'create');
            Route::patch('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

        Route::middleware(\App\Http\Middleware\MyAuthMiddleware::class)->group(function () {
            Route::get('/{id}', 'readById');
            Route::get('', 'read');
        });
    });


Route::controller(\App\Http\Controllers\BuildingController::class)
    ->prefix('buildings')->group(function () {
        Route::middleware(\App\Http\Middleware\MyAuthAdminMiddleware::class)->group(function () {
            Route::post('', 'create');
            Route::patch('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

        Route::middleware(\App\Http\Middleware\MyAuthMiddleware::class)->group(function () {
            Route::get('types', 'types');
            Route::get('/{id}', 'readById');
            Route::get('', 'read');
        });
    });


Route::controller(\App\Http\Controllers\PropertyController::class)
    ->prefix('properties')->group(function () {
        Route::middleware(\App\Http\Middleware\MyAuthAgentMiddleware::class)->group(function () {
            Route::post('', 'create');
            Route::patch('/{id}', 'update')->whereNumber('id');
//            Route::get('/self', 'self');
            Route::delete('/{id}', 'delete')->whereNumber('id');
        });

        Route::middleware(\App\Http\Middleware\MyAuthMiddleware::class)->group(function () {
            Route::get('/types', 'types');
            Route::get('/space-types', 'spaceTypes');
            Route::get('/{id}', 'readById')->whereNumber('id');
            Route::get('', 'read');
        });
    });

Route::get('/advertisements', [\App\Http\Controllers\AdvertisementController::class, 'read']);
Route::get('/advertisements/{id}', [\App\Http\Controllers\AdvertisementController::class, 'readById'])->whereNumber('id');
Route::controller(\App\Http\Controllers\AdvertisementController::class)
    ->prefix('advertisements')->group(function () {
        Route::middleware(\App\Http\Middleware\MyAuthAgentMiddleware::class)->group(function () {
            Route::post('', 'create');
//            Route::get('/self', 'self');
            Route::patch('/{id}', 'update')->whereNumber('id');
            Route::delete('/{id}', 'delete')->whereNumber('id');
        });

//        Route::middleware(\App\Http\Middleware\MyAuthMiddleware::class)->group(function () {
//            Route::get('/{id}', 'readById');
//        });
    });

Route::controller(\App\Http\Controllers\ViewRequestController::class)
    ->prefix('views')->group(function () {
        Route::middleware(\App\Http\Middleware\MyAuthAgentMiddleware::class)->group(function () {
            Route::post('/{id}/status', 'changeStatus');
            Route::get('/agent', 'readAgent');

        });

        Route::middleware(\App\Http\Middleware\MyAuthMiddleware::class)->group(function () {
            Route::post('', 'create');
            Route::patch('/{id}', 'update');
            Route::delete('/{id}', 'delete');
            Route::get('/user', 'readUser');
            Route::get('/{id}', 'readById');
        });
    });
