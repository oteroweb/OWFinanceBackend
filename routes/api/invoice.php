<?php
    
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::group([
    'middleware' => [
        'api',
    ],
    'prefix'     => 'api/1.0/invoices',
], function () {
  //Invoice ROUTES 
    Route::get('/all', ['uses'=> 'InvoiceController@all']);
    Route::get('/{id}', 'InvoiceController@find');
    Route::post('/save', 'InvoiceController@save');
    Route::put('/update/{id}', 'InvoiceController@update');
    Route::put('/change/active/{id}', 'InvoiceController@change_status');
    Route::delete('/delete/{id}', 'InvoiceController@delete');
  });