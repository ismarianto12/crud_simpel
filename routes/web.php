<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/pass', function () {
    return bcrypt(123);
});

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/logout', 'Auth\LoginController@logout');

if (env('APP_ENV') === 'production') {
    URL::forceSchema('https');
}

Route::group(['middleware' => ['auth']], function () {
    // 
    Route::resource('/home', 'HomeController');
    Route::resource('barang', 'TmbarangController');
    Route::post('barang/api', 'TmbarangController@api')->name('barang.api');
    Route::post('barang/edit_data', 'TmbarangController@edit_data')->name('barang.edit_data');
    Route::get('tmbarang/table', 'TmbarangController@api_table')->name('tmbarang.table');

    //pref
    Route::resource('userlogin', 'UserloginController');
    Route::post('userlogin/api', 'UserloginController@api')->name('userlogin.api');
    Route::get('userdata/table', 'UserloginController@table')->name('userdata.table');

    Route::resource('profil', 'ProfileController');
    Route::post('profil/updatesave','ProfileController@updatesave')->name('profil.updatesave');
});

Auth::routes();
