<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('user')->middleware(['auth:user'])->name('user.')->group(function () {
Route::get('/', function () {
    return view('welcome');
});
});

Route::controller(App\Http\Controllers\Logincontroller::class)->group(function() {
    Route::match(['get' , 'post'] , '/login' , 'userLogin')->name('userLogin');
    Route::match(['get' , 'post'] , '/user/logins' , 'userLogins')->name('userLogins');
});


Route::prefix('user')->middleware(['auth:user'])->name('user.')->group(function () {

    Route::controller(App\Http\Controllers\UserController::class)->group(function(){
        Route::match(['get' , 'post'] , '/' , 'index')->name('index');
    });

    Route::controller(App\Http\Controllers\CategoryController::class)->group(function(){
        Route::match(['get' , 'post'], '/category' , 'category')->name('category');
        Route::match(['get' , 'post'], '/category-save' , 'CategorySave')->name('category-save');
        Route::match(['get' , 'post'], '/category-list' , 'CategoryList')->name('CategoryList');
        Route::match(['get' , 'post'], '/category-delete' , 'CategoryDelete')->name('category-delete');
        Route::match(['get' , 'post'], '/category-edit' , 'CategoryUpdate')->name('category-edit');
    });

    Route::controller(App\Http\Controllers\ProductController::class)->group(function(){
        Route::match(['get' , 'post'] , 'product' , 'product')->name('product');
        Route::match(['get' , 'post'] , 'products' , 'productbyId')->name('productbyId');
        Route::match(['get' , 'post'] , 'product-save' , 'ProductSave')->name('product-save');
        Route::match(['get' , 'post'] , 'ProductList' , 'ProductList')->name('ProductList');
        Route::match(['get' , 'post'] , 'product-delete' , 'ProductDelete')->name('product-delete');
        Route::match(['get' , 'post'] , 'product-edit' , 'ProductEdit')->name('product-edit');
    });

    Route::controller(App\Http\Controllers\OccupationController::class)->group(function(){
        Route::match(['get' , 'post'] , 'occuption' , 'occuption')->name('occuption');
        Route::match(['get' , 'post'] , 'occuptionlist' , 'occuptionlist')->name('occuptionlist');
    });

    Route::controller(App\Http\Controllers\CompanyController::class)->group(function(){
        Route::match(['get' , 'post'] , 'company' , 'company')->name('company');
        Route::match(['get' , 'post'] , 'companylist' , 'companylist')->name('companylist');
    });

    Route::controller(App\Http\Controllers\CustomerController::class)->group(function(){
        Route::match(['get' , 'post'] , 'customer' , 'customer')->name('customer');
        Route::match(['get' , 'post'] , 'exist-customer' , 'existcustomer')->name('exist-customer');
        Route::match(['get' , 'post'] , 'checkcustomer' , 'checkcustomer')->name('checkcustomer');
        Route::match(['get' , 'post'] , 'verify-customer' , 'verifycustomer')->name('verify-customer');
        Route::match(['get' , 'post'] , 'verify-customer/list' , 'verifycustomerlist')->name('verify-customer-list');

        Route::match(['get' , 'post'] , 'customer-save' , 'customersave')->name('customer-save');
        // Route::match(['get' , 'post'] , 'companylist' , 'companylist')->name('companylist');
    });

    Route::controller(App\Http\Controllers\EligibleController::class)->group(function(){
        Route::match(['get' , 'post'] , 'eligible' , 'eligible')->name('eligible');
        // Route::match(['get' , 'post'] , 'customer-save' , 'customersave')->name('customer-save');
    });

    Route::controller(App\Http\Controllers\PincodeController::class)->group(function(){
        Route::match(['get' , 'post'] , 'pincode' , 'pincode')->name('pincode');
        // Route::match(['get' , 'post'] , 'customer-save' , 'customersave')->name('customer-save');
    });

});
