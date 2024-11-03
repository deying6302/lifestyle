<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| This is where you can register administrative routes for your application.
| This route is loaded by RouteServiceProvider in a "web" middleware group
| that contains all the middleware for the web application.
|
*/



Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('make-role-admin', function () {
        Admin::create([
            'full_name' => 'Sin Dang',
            'user_name' => 'Sin2x',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'phone' => '01288032567',
            'address' => 'HN',
            'gender' => 'male'
        ]);

        return redirect()->back();
    });

    Route::group(['middleware' => ['CheckAdminSession']], function () {
        Route::get('/', 'Auth\LoginController@getLogin')->name('getLogin');
        Route::post('/', 'Auth\LoginController@postLogin')->name('postLogin');
    });

    Route::group(['middleware' => ['CheckAdminLogin', 'AdminLanguageSession']], function () {
        Route::get('logout', 'AdminController@logout')->name('logout');

        // Optimize
        Route::get('/clear-cache', 'OptimizationController@clearCache');
        Route::get('/migrate', 'OptimizationController@migrate');
        Route::get('/migrate-fresh', 'OptimizationController@migrateFresh');

        Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('/system-info', 'AdminController@systemInfo')->name('system.info');

        Route::get('/language/switch/{lang}', 'LanguageController@switchLang')->name('language.switch');

        // Category
        Route::name('category.')->prefix('category')->group(function () {
            Route::get('/', 'CategoryController@index')->name('index');
            Route::post('/store', 'CategoryController@store')->name('store');
            Route::get('/edit', 'CategoryController@edit')->name('edit');
            Route::put('/update', 'CategoryController@update')->name('update');
            Route::delete('/delete', 'CategoryController@delete')->name('delete');
            Route::delete('/delete-all', 'CategoryController@deleteAll')->name('delete.all');
            Route::post('/restore', 'CategoryController@restore')->name('restore');
            Route::post('/restore-all', 'CategoryController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'CategoryController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'CategoryController@forceDeleteAll')->name('force.delete.all');

            Route::get('/select-category', 'CategoryController@selectCategory')->name('select');
        });

        // Subcategory
        Route::name('subcategory.')->prefix('subcategory')->group(function () {
            Route::get('/list', 'SubcategoryController@list')->name('list');
            Route::get('/sub-edit', 'SubcategoryController@subEdit')->name('sub.edit');
            Route::put('/update', 'SubcategoryController@update')->name('update');
            Route::delete('/delete', 'SubcategoryController@delete')->name('delete');
            Route::delete('/delete-all', 'SubcategoryController@deleteAll')->name('delete.all');
            Route::post('/restore', 'SubcategoryController@restore')->name('restore');
            Route::post('/restore-all', 'SubcategoryController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'SubcategoryController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'SubcategoryController@forceDeleteAll')->name('force.delete.all');
        });

        // Brand
        Route::name('brand.')->prefix('brand')->group(function () {
            Route::get('/', 'BrandController@index')->name('index');
            Route::post('/store', 'BrandController@store')->name('store');
            Route::get('/edit', 'BrandController@edit')->name('edit');
            Route::put('/update', 'BrandController@update')->name('update');
            Route::delete('/delete', 'BrandController@delete')->name('delete');
            Route::delete('/delete-all', 'BrandController@deleteAll')->name('delete.all');
            Route::post('/restore', 'BrandController@restore')->name('restore');
            Route::post('/restore-all', 'BrandController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'BrandController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'BrandController@forceDeleteAll')->name('force.delete.all');
        });

        // Product
        Route::name('product.')->prefix('product')->group(function () {
            Route::get('/', 'ProductController@index')->name('index');
            Route::post('/store', 'ProductController@store')->name('store');
            Route::get('/edit', 'ProductController@edit')->name('edit');
            Route::put('/update', 'ProductController@update')->name('update');
            Route::delete('/delete', 'ProductController@delete')->name('delete');
            Route::delete('/delete-all', 'ProductController@deleteAll')->name('delete.all');
            Route::post('/restore', 'ProductController@restore')->name('restore');
            Route::post('/restore-all', 'ProductController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'ProductController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'ProductController@forceDeleteAll')->name('force.delete.all');

            Route::get('/load-subcategory', 'ProductController@loadSubcategory')->name('load.subcategory');
            Route::get('/load-categories', 'ProductController@loadCategories')->name('load.categories');
        });

        // Gallery
        Route::name('gallery.')->prefix('gallery')->group(function () {
            Route::get('/', 'GalleryController@index')->name('index');
            Route::post('/store', 'GalleryController@store')->name('store');
            Route::delete('/delete', 'GalleryController@delete')->name('delete');
            Route::delete('/delete-all', 'GalleryController@deleteAll')->name('delete.all');
            Route::post('/restore', 'GalleryController@restore')->name('restore');
            Route::post('/restore-all', 'GalleryController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'GalleryController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'GalleryController@forceDeleteAll')->name('force.delete.all');
        });

        // Color
        Route::name('color.')->prefix('color')->group(function () {
            Route::get('/', 'ColorController@index')->name('index');
            Route::post('/store', 'ColorController@store')->name('store');
            Route::get('/edit', 'ColorController@edit')->name('edit');
            Route::put('/update', 'ColorController@update')->name('update');
            Route::delete('/delete', 'ColorController@delete')->name('delete');
            Route::delete('/delete-all', 'ColorController@deleteAll')->name('delete.all');
            Route::post('/restore', 'ColorController@restore')->name('restore');
            Route::post('/restore-all', 'ColorController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'ColorController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'ColorController@forceDeleteAll')->name('force.delete.all');
        });

        // Size
        Route::name('size.')->prefix('size')->group(function () {
            Route::get('/', 'SizeController@index')->name('index');
            Route::post('/store', 'SizeController@store')->name('store');
            Route::get('/edit', 'SizeController@edit')->name('edit');
            Route::put('/update', 'SizeController@update')->name('update');
            Route::delete('/delete', 'SizeController@delete')->name('delete');
            Route::delete('/delete-all', 'SizeController@deleteAll')->name('delete.all');
            Route::post('/restore', 'SizeController@restore')->name('restore');
            Route::post('/restore-all', 'SizeController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'SizeController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'SizeController@forceDeleteAll')->name('force.delete.all');
        });

        // Coupon
        Route::name('coupon.')->prefix('coupon')->group(function () {
            Route::get('/', 'CouponController@index')->name('index');
            Route::post('/store', 'CouponController@store')->name('store');
            Route::get('/edit', 'CouponController@edit')->name('edit');
            Route::put('/update', 'CouponController@update')->name('update');
            Route::delete('/delete', 'CouponController@delete')->name('delete');
            Route::delete('/delete-all', 'CouponController@deleteAll')->name('delete.all');
            Route::post('/restore', 'CouponController@restore')->name('restore');
            Route::post('/restore-all', 'CouponController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'CouponController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'CouponController@forceDeleteAll')->name('force.delete.all');
        });

        // Blog
        Route::name('blog.')->prefix('blog')->group(function () {
            Route::get('/', 'BlogController@index')->name('index');
            Route::post('/store', 'BlogController@store')->name('store');
            Route::get('/edit', 'BlogController@edit')->name('edit');
            Route::put('/update', 'BlogController@update')->name('update');
            Route::delete('/delete', 'BlogController@delete')->name('delete');
            Route::delete('/delete-all', 'BlogController@deleteAll')->name('delete.all');
            Route::post('/restore', 'BlogController@restore')->name('restore');
            Route::post('/restore-all', 'BlogController@restoreAll')->name('restore.all');
            Route::delete('/force-delete', 'BlogController@forceDelete')->name('force.delete');
            Route::delete('/force-delete-all', 'BlogController@forceDeleteAll')->name('force.delete.all');

            Route::post('/change-status', 'BlogController@changeStatus')->name('change.status');
        });

        // Subscriber
        Route::group(['prefix' => 'subscriber'], function () {
            Route::get('/', 'SubscriberController@index')->name('subscriber.index');
        });

        // General Setting
        Route::name('setting.')->prefix('setting')->group(function () {
            Route::get('/general', 'GeneralSettingController@general')->name('general');
            Route::post('/general', 'GeneralSettingController@generalSubmit')->name('general.submit');
            Route::get('/optimize', 'GeneralSettingController@optimize')->name('optimize');
            Route::get('/cookie', 'GeneralSettingController@cookie')->name('cookie');
            Route::post('/cookie', 'GeneralSettingController@cookieSubmit')->name('cookie.submit');
            Route::get('/logo-icon', 'GeneralSettingController@logoIcon')->name('logo.icon');
            Route::post('/logo-icon', 'GeneralSettingController@logoIconSubmit')->name('logo.icon.submit');
        });

        // Frontend
        Route::get('/seo', 'FrontendController@seo')->name('seo');
        Route::post('/seo', 'FrontendController@seoSubmit')->name('seo.submit');

        Route::name('frontend.')->prefix('frontend')->group(function () {
            // Contact
            Route::get('/contact', 'FrontendController@contact')->name('contact');
            Route::post('/contact', 'FrontendController@contactSubmit')->name('contact.submit');

            // Policy
            Route::name('policy.')->prefix('policy')->group(function () {
                Route::get('/', 'PolicyController@index')->name('index');
                Route::post('/store', 'PolicyController@store')->name('store');
                Route::get('/edit', 'PolicyController@edit')->name('edit');
                Route::put('/update', 'PolicyController@update')->name('update');
                Route::delete('/delete', 'PolicyController@delete')->name('delete');
                Route::delete('/delete-all', 'PolicyController@deleteAll')->name('delete.all');
                Route::post('/restore', 'PolicyController@restore')->name('restore');
                Route::post('/restore-all', 'PolicyController@restoreAll')->name('restore.all');
                Route::delete('/force-delete', 'PolicyController@forceDelete')->name('force.delete');
                Route::delete('/force-delete-all', 'PolicyController@forceDeleteAll')->name('force.delete.all');
                Route::post('/change-status', 'PolicyController@changeStatus')->name('change.status');
            });

            // Slider
            Route::name('slider.')->prefix('slider')->group(function () {
                Route::get('/', 'SliderController@index')->name('index');
                Route::post('/store', 'SliderController@store')->name('store');
                Route::get('/edit', 'SliderController@edit')->name('edit');
                Route::put('/update', 'SliderController@update')->name('update');
                Route::delete('/delete', 'SliderController@delete')->name('delete');
                Route::delete('/delete-all', 'SliderController@deleteAll')->name('delete.all');
                Route::post('/restore', 'SliderController@restore')->name('restore');
                Route::post('/restore-all', 'SliderController@restoreAll')->name('restore.all');
                Route::delete('/force-delete', 'SliderController@forceDelete')->name('force.delete');
                Route::delete('/force-delete-all', 'SliderController@forceDeleteAll')->name('force.delete.all');
                Route::post('/change-status', 'SliderController@changeStatus')->name('change.status');
            });

            // Social Icon
            Route::name('social_icon.')->prefix('social_icon')->group(function () {
                Route::get('/', 'SocialController@index')->name('index');
                Route::post('/store', 'SocialController@store')->name('store');
                Route::get('/edit', 'SocialController@edit')->name('edit');
                Route::put('/update', 'SocialController@update')->name('update');
                Route::delete('/delete', 'SocialController@delete')->name('delete');
                Route::delete('/delete-all', 'SocialController@deleteAll')->name('delete.all');
                Route::post('/restore', 'SocialController@restore')->name('restore');
                Route::post('/restore-all', 'SocialController@restoreAll')->name('restore.all');
                Route::delete('/force-delete', 'SocialController@forceDelete')->name('force.delete');
                Route::delete('/force-delete-all', 'SocialController@forceDeleteAll')->name('force.delete.all');
                Route::post('/change-status', 'SocialController@changeStatus')->name('change.status');
            });
        });
    });
});
