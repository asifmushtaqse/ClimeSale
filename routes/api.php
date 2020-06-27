<?php

use App\Product;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('products')->group(function(){
    Route::get('/', 'ProductController@index');
    Route::post('/', 'ProductController@store');
    Route::get('/{id}', 'ProductController@show');
    Route::put('/{id}', 'ProductController@update');
    Route::delete('/{id}', 'ProductController@destroy');
});
Route::prefix('stocks')->group(function(){
    Route::get('/', 'StockController@index');
    Route::prefix('in')->group(function(){
        Route::get('/', 'StockInController@index');
        Route::post('/', 'StockInController@store');
        Route::get('/{id}', 'StockInController@show');
        Route::put('/{id}', 'StockInController@update');
        Route::delete('/{id}', 'StockInController@destroy');
    });
    Route::prefix('out')->group(function(){
        Route::get('/', 'StockOutController@index');
        Route::post('/', 'StockOutController@store');
        Route::get('/{id}', 'StockOutController@show');
        Route::put('/{id}', 'StockOutController@update');
        Route::delete('/{id}', 'StockOutController@destroy');
    });
    Route::prefix('return')->group(function(){
        Route::get('/', 'ReturnStockController@index');
        Route::post('/', 'ReturnStockController@store');
        Route::get('/{id}', 'ReturnStockController@show');
        Route::put('/{id}', 'ReturnStockController@update');
        Route::delete('/{id}', 'ReturnStockController@destroy');
    });
    Route::prefix('salesman')->group(function(){
        Route::get('/', 'SaleManStockController@index');
        Route::post('/', 'SaleManStockController@store');
        Route::delete('/{id}', 'SaleManStockController@destroy');
    });
});
Route::prefix('expenses')->group(function(){
    Route::get('/', 'ExpenseController@index');
    Route::post('/', 'ExpenseController@store');
    Route::get('/{id}', 'ExpenseController@show');
    Route::put('/{id}', 'ExpenseController@update');
    Route::delete('/{id}', 'ExpenseController@destroy');
});
Route::prefix('customers')->group(function(){
    Route::get('/', 'CustomerController@index');
    Route::post('/', 'CustomerController@store');
    Route::get('/{id}', 'CustomerController@show');
    Route::put('/{id}', 'CustomerController@update');
    Route::delete('/{id}', 'CustomerController@destroy');
});
Route::prefix('summary')->group(function(){
    Route::get('/', 'SummaryController@index');
    Route::post('/', 'SummaryController@store');
    Route::get('/{id}', 'SummaryController@show');
    Route::put('/{id}', 'SummaryController@update');
    Route::delete('/{id}', 'SummaryController@destroy');
});
Route::prefix('reports')->group(function(){
    Route::prefix('profit')->group(function(){
        Route::get('/', 'ReportController@profit');
    });
});

Route::get('test', function(){
    return Product::find(1)->price();
});

