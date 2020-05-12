<?php
    
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::group([
    'middleware' => [
        'api',
    ],
    'prefix'     => 'api/1.0/items',
], function () {
  //Item ROUTES 
    Route::get('/all', ['uses'=> 'ItemController@all']);
    Route::get('/{id}', 'ItemController@find');
    Route::post('/save', 'ItemController@save');
    Route::put('/update/{id}', 'ItemController@update');
    Route::put('/change/active/{id}', 'ItemController@change_status');
    Route::delete('/delete/{id}', 'ItemController@delete');
  });