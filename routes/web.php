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

Route::get('/', 'Home')->name('home');

Auth::routes([
    'register' => false,
    'verify' => false,
]);

Route::namespace('Auth')->group(function () {
    Route::get('/redirect', 'SocialAuthGoogleController@redirect');
    Route::get('/callback', 'SocialAuthGoogleController@callback');
});

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
        Route::resource('users', 'UserController');
        Route::group(['namespace' => 'Admin'], function () {
            Route::get('webhooks', 'WebhookController@index')->name('admin.webhooks.index');
            Route::get('webhooks/{webhook}', 'WebhookController@show')->name('admin.webhooks.show');
            Route::get('history', 'PayloadHistoryController@index')->name('admin.history.index');
            Route::get('history/{history}', 'PayloadHistoryController@show')->name('admin.history.show');
            Route::delete('history/{history}', 'PayloadHistoryController@destroy')->name('admin.history.destroy');
            Route::delete('history/message/{message}', 'MessageHistoryController@destroy')->name('admin.message.destroy');
            Route::resource('dashboard', 'DashboardController')->only('index');
        });
    });
    Route::resource('bots', 'BotController')->except('show');
    Route::put('webhooks/change_status', 'WebhookController@changeStatus');
    Route::resource('webhooks', 'WebhookController');
    Route::resource('history', 'PayloadHistoryController')->only(['show', 'index', 'destroy']);
    Route::post('history/recheck', 'PayloadHistoryController@recheck')->name('history.recheck');
    Route::resource('history/message', 'MessageHistoryController')->only(['destroy']);
    Route::resource('rooms', 'RoomController')->only([
        'index'
    ]);
    Route::resource('webhooks.payloads', 'PayloadController')->except('show');
    Route::resource('webhooks.mappings', 'MappingController')->except(['index', 'show', 'update']);
    Route::get('webhooks/{webhook}/mappings', 'MappingController@edit')->name('webhooks.edit.mappings');
    Route::post('webhooks/{webhook}/mappings/update', 'MappingController@update')->name('webhooks.update.mappings');
    Route::resource('dashboard', 'DashboardController')->only('index');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('admin');
    Route::put('templates/change_status', 'TemplateController@changeStatus');
    Route::resource('templates', 'TemplateController');
});
