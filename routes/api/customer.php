<?php
    
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::group([
    'middleware' => [
        'api',
    ],
    'prefix'     => 'api/1.0/customer',
], function () {
  //Customer ROUTES 
    Route::get('/all', ['uses'=> 'CustomerController@all']);
    Route::get('/{id}', 'CustomerController@find');
    Route::post('/save', 'CustomerController@save');
    Route::put('/update/{id}', 'CustomerController@update');
    Route::put('/change/active/{id}', 'CustomerController@change_status');
    Route::delete('/delete/{id}', 'CustomerController@delete');
  });