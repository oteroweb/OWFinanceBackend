<?php
    
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::group([
    'middleware' => [
        'api',
    ],
    'prefix'     => 'api/1.0/accounts',
], function () {
  //Account ROUTES 
    Route::get('/all', ['uses'=> 'AccountController@all']);
    Route::get('/{id}', 'AccountController@find');
    Route::post('/save', 'AccountController@save');
    Route::put('/update/{id}', 'AccountController@update');
    Route::put('/change/active/{id}', 'AccountController@change_status');
    Route::delete('/delete/{id}', 'AccountController@delete');
  });