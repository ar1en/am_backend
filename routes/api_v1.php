<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Роуты авторизации*/
Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\auth'], function(){
    Route::post('/login', 'LoginController')->name('auth.login');
    Route::post('/logout', 'LogoutController')->middleware('auth:sanctum');
    //Route::get('/user', 'UserController')->middleware('auth:sanctum')->middleware('web');
});

/*Route::group(['prefix' => 'users', 'namespace' => 'App\Http\Controllers\api\v1\User', 'middleware' => 'auth:sanctum'], function() {
   Route::get('/', 'IndexController')->name('user.index');
   Route::get('/{id}', 'ShowController')->name('user.show');
   Route::post('/', 'StoreController')->name('user.store');
});*/

/*Группа роутов универсальных контроллеров. Должна располагаться после всех остальных групп контроллеров*/
Route::group(['prefix' => '', 'namespace' => 'App\Http\Controllers\api\v1\Universal', 'middleware' => 'auth:sanctum'], function() {
   Route::get('{model}/{with?}', 'IndexController')->name('universal.index')
       ->where('with', '[^0-9-]+(?:-[a-zA-Z0-9]+)*');
   Route::get('{model}/{id}/{with?}', 'ShowController')->name('universal.show')
       ->where('id', '[a-f0-9]{8}-[a-f0-9]{4}-[1-5][a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}')
       ->where('with', '[^0-9-]+(?:-[a-zA-Z0-9]+)*');
   // ->where('id', '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}');
   Route::post('{model}', 'StoreController')->name('universal.store');
   Route::put('{model}/{id}', 'UpdateController')->name('universal.update');
   //    ->where('id', '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}');
});



