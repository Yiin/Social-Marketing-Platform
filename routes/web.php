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

use App\Queue;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::name('dashboard')->get('/', 'DashboardController@index');
    Route::name('profile')->get('my-profile', 'DashboardController@profile');

    Route::resource('user', 'UserController');
    Route::name('user.change-password')->patch('change-password/{user}', 'UserController@changePassword');

    Route::resource('client', 'ClientController');

    Route::name('google-plus')->get('google-plus', 'GooglePlusController@index');
    Route::get('api/google-plus/accounts', 'GooglePlusController@accounts');
    Route::post('api/google-plus/add-account', 'GooglePlusController@addAccount');
    Route::post('api/google-plus/logout-account', 'GooglePlusController@logoutAccount');
    Route::post('api/google-plus/communities', 'GooglePlusController@accountCommunities');
    Route::post('api/google-plus/post', 'GooglePlusController@post');

    Route::get('statistics/{queue}', function (Queue $queue) {
        return view('statistic')->with(compact('queue'));
    });

    Route::get('wtf', function () {
        session()->flush();
    });

    Route::get('session', function () {
        dd(session()->all());
    });
});