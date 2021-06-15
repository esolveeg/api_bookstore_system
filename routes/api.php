<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/uptown', function (Request $request) {
    $branch = 4;
    $chart_data = \App\Order::select(
        DB::raw('YEAR(created_at) as year'),
        DB::raw('MONTH(created_at) as month'),
        DB::raw('DAY(created_at) as day'),
        DB::raw('created_at as date'),
        DB::raw('SUM(total) as total')
    )->groupBy('day')->where('branch_id', $branch);
    isset($request->from) ? $chart_data = $chart_data->where('created_at', '>=', $request->from) : '';
    isset($request->to) ? $chart_data = $chart_data->where('created_at', '<', $request->to) : '';
    return $chart_data->get();
});

Route::middleware('guest:api')->group(function () {
    Route::post('/login', 'ProductController@login')->name('api.login');
    Route::post('/login2', 'ProductController@loginn')->name('api.loginn');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});
Route::middleware('auth:api')->group(function () {
    Route::get('/products', 'ProductController@getProducts');
    Route::get('/transactions', 'ProductController@getTransactions');
    Route::post('/deleteById', 'ProductController@deleteById');

    Route::get('/user/permissions', 'ProductController@getUserPermsissions');
    Route::get('/products/checknext', 'ProductController@checkProducts');
    Route::get('/allbranches', 'FiltersController@getBranches');
    Route::get('/product/{isbn}', 'ProductController@getProduct');
    Route::get('/orders', 'ProductController@getOrders');
    Route::get('/branches', 'ProductController@getBranches');
    Route::get('/order/{id}', 'ProductController@getOrder');
    Route::post('/order/{id}/edit', 'ProductController@editOrder');
    Route::post('/checkout', 'ProductController@checkout');
    Route::get('/outorder/{id}', 'ProductController@getOutOrder');
    Route::post('/outorder/{id}/edit', 'ProductController@editOutOrder');
    Route::post('/outorder', 'ProductController@createOutOrder');
    Route::post('/transaction/create', 'TransactionController@createTransaction');
    Route::post('/transaction/{id}/edit', 'TransactionController@editTransaction');
    Route::post('/transaction/{id}/approve', 'TransactionController@approveTransaction');
    Route::get('/transaction/{id}', 'TransactionController@getTransaction');
    Route::get('/cart', 'CartController@getCart');
    Route::post('/cart', 'CartController@addToCart');
    Route::post('/cart/discount', 'CartController@addDiscountToCart');
    Route::delete('/cart/{id}', 'CartController@deleteFromCart');
    Route::delete('/cart', 'CartController@destroyCart');
    Route::put('/cart', 'CartController@updateCart');
    Route::post('/checkoutnew', 'CheckoutController@checkout');
    Route::post('/order/{id}/editnew', 'CheckoutController@editOrder');
    Route::get('/ordernew/{id}', 'CheckoutController@getOrder');
    Route::get('/productnew/{id}', 'ProductController@getFullProduct');
    Route::post('/order/{id}/rollback', 'CheckoutController@rollBackOrder');
    Route::post('/order/{id}/update', 'CheckoutController@updateOrder');
    Route::delete('/order/{id}/delete', 'CheckoutController@deleteOrder');

});
