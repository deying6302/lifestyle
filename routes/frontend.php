<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['UserLanguageSession']], function () {

    Route::get('/language/switch/{lang}', 'LanguageController@switchLang')->name('language.switch');

    Route::get('/', 'SiteController@home')->name('home');
    Route::get('/about', 'SiteController@about')->name('about');
    Route::get('/contact', 'SiteController@contact')->name('contact');
    Route::get('/blog', 'SiteController@blog')->name('blog');
    Route::get('/blog-details/{slug}', 'SiteController@blogDetails')->name('blog.details');
    Route::get('/policy/{slug}', 'SiteController@policy')->name('policy');
    Route::get('/faqs', 'SiteController@faqs')->name('faqs');

    Route::get('/shop', 'ShopController@index')->name('shop');
    Route::get('/product-detail/{slug}', 'ShopController@productDetails')->name('product.details');

    Route::get('/quick-to-view', 'ShopController@quickToView')->name('quick.to.view');
    Route::post('/add-to-cart', 'ShopController@addToCart')->name('add.to.cart');

    Route::get('/load-cart-count', 'BasicController@loadCartCount')->name('load.cart.count');
    Route::get('/load-cart-dropdown', 'BasicController@loadCartDropdown')->name('load.cart.dropdown');
    Route::post('/shipping-address-store', 'BasicController@shippingAddressStore')->name('shipping.address.store');
    Route::get('/shipping-address-edit', 'BasicController@shippingAddressEdit')->name('shipping.address.edit');
    Route::put('/shipping-address-update', 'BasicController@shippingAddressUpdate')->name('shipping.address.update');

    Route::get('/complete', 'OrderController@complete')->name('complete');

    Route::get('/check-auth', function() {
        return response()->json(['isLoggedIn' => Auth::guard('customer')->check()]);
    })->name('check.auth');

    Route::name('cart.')->prefix('cart')->group(function () {
        Route::get('/', 'CartController@index')->name('index');
        Route::get('/load-view', 'CartController@loadView')->name('load.view');
        Route::delete('/remove-item', 'CartController@removeItem')->name('remove.item');
        Route::delete('/delete-selected-item', 'CartController@deleteSelectedItem')->name('delete.selected.item');
        Route::delete('/remove-all-item', 'CartController@removeAllItem')->name('remove.all.item');
        Route::post('/update-quantity', 'CartController@updateQuantity')->name('update.quantity');
        Route::post('/apply-coupon', 'CartController@applyCoupon')->name('apply.coupon');
        Route::post('/shipping-fee', 'CartController@getShippingFee')->name('shipping.fee');
        Route::post('/save-coupon-session', 'CartController@saveCouponSession')->name('save.coupon.session');
        Route::get('/forget-coupon-session', 'CartController@forgetCouponSession')->name('forget.coupon.session');
        Route::post('/save-shipping-method-session', 'CartController@saveShippingMethodSession')->name('save.shipping.method.session');
    });

    Route::name('checkout.')->prefix('checkout')->group(function () {
        Route::get('/', 'CheckoutController@index')->name('index');
        Route::get('/load-view', 'CheckoutController@loadView')->name('load.view');
        Route::get('/load-province', 'CheckoutController@loadProvince')->name('load.province');
        Route::get('/load-district', 'CheckoutController@loadDistrict')->name('load.district');
        Route::get('/load-ward', 'CheckoutController@loadWard')->name('load.ward');
    });

    Route::name('payment.')->prefix('payment')->group(function () {
        Route::get('/', 'PaymentController@index')->name('index');
    });

    Route::namespace('Auth')->prefix('buyer')->name('buyer.')->group(function () {
        Route::get('auth', 'LoginController@getAuth')->name('auth');
        Route::post('auth-login', 'LoginController@postAuthLogin')->name('auth.login');
        Route::post('auth-register', 'LoginController@postAuthRegister')->name('auth.register');
    });
});

