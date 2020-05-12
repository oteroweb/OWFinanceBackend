<?php
    
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::group([
    'middleware' => [
        'api',
    ],
    'prefix'     => 'api/1.0/transactions',
], function () {
  //Transaction ROUTES 
    Route::get('/all', ['uses'=> 'TransactionController@all']);
    Route::get('/{id}', 'TransactionController@find');
    Route::post('/save', 'TransactionController@save');
    Route::put('/update/{id}', 'TransactionController@update');
    Route::put('/change/active/{id}', 'TransactionController@change_status');
    Route::delete('/delete/{id}', 'TransactionController@delete');
  });