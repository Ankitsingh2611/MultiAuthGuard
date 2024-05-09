<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('verify-account/{token}', [UserController::class, 'verifyAccount'])->name('verify.account');
Route::post('get-states', [UserController::class, 'getStates']);
Route::post('get-cities', [UserController::class, 'getCities']);
Route::get('reset-password/{token}', [UserController::class, 'resetPassword'])->name('reset-password');
Route::post('reset-password', [UserController::class, 'resetPasswordSubmit'])->name('reset-password');

Route::prefix('user')->name('user.')->group(function(){
    Route::middleware(['guest:web'])->group(function () {
        Route::view('/login','dashboard.user.login')->name('login');
        Route::get('/register',[UserController::class,'register'])->name('register');
        Route::post('/create',[UserController::class,'create'])->name('create');
        Route::post('/dologin',[UserController::class,'dologin'])->name('dologin');
        Route::get('laravel-dropdown', [UserController::class, 'index']);
        Route::view('/forgot_password','dashboard.user.forgot_password')->name('forgot_password');
        Route::get('refresh_captcha', [UserController::class,'refreshCaptcha'])->name('refresh_captcha');
        Route::post('/forgotpwdpost',[UserController::class,'forgotpwdpost'])->name('forgotpwdpost');

    });
    Route::middleware(['auth:web'])->group(function () {
        Route::view('/home','dashboard.user.home')->name('home');
        Route::post('/logout',[UserController::class,'logout'])->name('logout');
        Route::get('/editprofile',[UserController::class,'editprofile'])->name('editprofile');
        Route::view('/myprofile','dashboard.user.myprofile')->name('myprofile');
        Route::view('/change_password','dashboard.user.change_password')->name('change_password');
        Route::post('/update', [UserController::class, 'update'])->name('update');
        Route::post('/password-update', [UserController::class, 'password_update'])->name('password-update');
        Route::get('/products', [UserController::class, 'products'])->name('products');
        Route::get('/add-to-cart/{id}', [UserController::class, 'addToCart'])->name('addToCart');
        Route::get('/cart', [UserController::class, 'cart'])->name('cart');
        Route::post('/delete-cart-product', [UserController::class, 'deleteCartProduct'])->name('delete.cart.product');
        Route::get('/order', [UserController::class, 'order'])->name('order');
        Route::post('/save-order', [UserController::class, 'orderSave'])->name('save.order');
        Route::get('/payment/{id}', [UserController::class, 'payment'])->name('payment');
        Route::post('/payment-save', [UserController::class, 'paymentSave'])->name('payment.save');
        Route::get('orders',[UserController::class, 'orders'])->name('orders');
        Route::get('order-details/{id}',[UserController::class, 'order_details'])->name('order.details');
    });
});

Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest:admin'])->group(function () {
        Route::view('/login','dashboard.admin.login')->name('login');
        Route::post('/dologin',[AdminController::class,'dologin'])->name('dologin');
    });
    Route::middleware(['auth:admin'])->group(function () {
        Route::view('/home','dashboard.admin.home')->name('home');
        Route::view('/editprofile','dashboard.admin.editprofile')->name('editprofile');
        Route::post('/update',[AdminController::class,'update'])->name('update');
        Route::post('/logout',[AdminController::class,'logout'])->name('logout');
        Route::view('/change_password','dashboard.admin.change_password')->name('change_password');
        Route::post('/password-update', [AdminController::class, 'password_update'])->name('password-update');
        Route::get('dashboard',[AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('users',[AdminController::class, 'users'])->name('users');
        Route::get('edit-user/{id}',[AdminController::class, 'editUser']);
        Route::post('update-user',[AdminController::class, 'updateUser'])->name('update.user');
        Route::get('products',[AdminController::class, 'products'])->name('products');
        Route::get('add-product',[AdminController::class, 'add_product'])->name('add.product');
        Route::post('save-product', [AdminController::class, 'saveProduct'])->name('admin.add.product');
        Route::get('edit-product/{id}',[AdminController::class, 'editProduct']);
        Route::post('update-product',[AdminController::class, 'updateProduct']);
        Route::get('delete-product/{id}',[AdminController::class, 'deleteProduct']);
        Route::get('orders',[AdminController::class, 'orders'])->name('orders');
    });
});



