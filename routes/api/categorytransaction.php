<?php
    
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::group([
    'middleware' => [
        'api',
    ],
    'prefix'     => 'api/1.0/category_transaction',
], function () {
  //CategoryTransaction ROUTES 
    Route::get('/all', ['uses'=> 'CategoryTransactionController@all']);
    Route::get('/{id}', 'CategoryTransactionController@find');
    Route::post('/save', 'CategoryTransactionController@save');
    Route::put('/update/{id}', 'CategoryTransactionController@update');
    Route::put('/change/active/{id}', 'CategoryTransactionController@change_status');
    Route::delete('/delete/{id}', 'CategoryTransactionController@delete');
  });