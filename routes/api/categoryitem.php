<?php
    
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::group([
    'middleware' => [
        'api',
    ],
    'prefix'     => 'api/1.0/category_items',
], function () {
  //CategoryItem ROUTES 
    Route::get('/all', ['uses'=> 'CategoryItemController@all']);
    Route::get('/{id}', 'CategoryItemController@find');
    Route::post('/save', 'CategoryItemController@save');
    Route::put('/update/{id}', 'CategoryItemController@update');
    Route::put('/change/active/{id}', 'CategoryItemController@change_status');
    Route::delete('/delete/{id}', 'CategoryItemController@delete');
  });