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

    Route::name('facebook')->get('facebook', 'FacebookController@index');
    Route::get('api/facebook/accounts', 'FacebookController@accounts');
    Route::post('api/facebook/add-account', 'FacebookController@addAccount');
    Route::post('api/facebook/logout-account', 'FacebookController@logoutAccount');
    Route::post('api/facebook/communities', 'FacebookController@accountCommunities');
    Route::post('api/facebook/post', 'FacebookController@post');

    Route::name('linkedin')->get('linkedin', 'LinkedInController@index');
    Route::get('api/linkedin/accounts', 'LinkedInController@accounts');
    Route::post('api/linkedin/add-account', 'LinkedInController@addAccount');
    Route::post('api/linkedin/logout-account', 'LinkedInController@logoutAccount');
    Route::post('api/linkedin/communities', 'LinkedInController@accountCommunities');
    Route::post('api/linkedin/post', 'LinkedInController@post');

    Route::get('statistics/{queue}', function (Queue $queue) {
        return view('statistic')->with(compact('queue'));
    });
});