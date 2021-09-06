<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return 'Testing it works';
});

Route::get('/test', 'ExampleController@getThings');
Route::get('/get-data', 'ExampleController@getSampleData');
Route::get('/get-env', 'ExampleController@getSampleEnv');
Route::get('/curl', 'PayTabsController@simpleCurl');
Route::post('/sample', 'PayTabsController@playGround');


// main running routes
Route::post('/create-merchant', 'PayTabsController@createMerchant');
Route::post('/payments/new_payment', 'PaytabsCallBackController@newPayment');
Route::get('/payments/details', 'PaymentDetails@getPaymentDetails');
