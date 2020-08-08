<?php

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

Route::get('/', function () {
    return view('welcome'); 
});

Auth::routes();


Route::post('add-name/', [
    "as" => "add-name-to-db",
    "uses" => "TodoController@store" 
]);
   
Route::post('reove-name/', [
    "as" => "remove-name-to-db",
    "uses" => "TodoController@destroy" 
]);

Route::get('all-records/', [
    "as" => "all-records",
    "uses" => "TodoController@index" 
]);
      
    
    
    

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('category','CategoryController');
Route::resource('role','RoleController');
Route::resource('user','UserController');




Route::get('profile', function(){
    return view('profile');
});

/* View Composer*/
View::composer(['*'], function($view){
    
    $user = Auth::user();
    $view->with('user',$user);
    

    

});

