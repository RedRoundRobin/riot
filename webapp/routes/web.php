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

Route::redirect('/', 'dashboard');

Auth::routes(['register' => false, 'reset' => false]);

//le Route DEVONO essere ordinate secondo logica di matching "if"

Route::get('login/tfa', 'Auth\LoginController@showTfaForm')->name('tfaLogin');

//routes protette dai guests
Route::middleware('auth')->group(function () {

    //routes per la gestione delle views
    Route::get('views', 'ViewController@index')->name('views.index');
    Route::get('views/{viewId}', 'ViewController@show')->name('views.show');
    Route::post('views', 'ViewController@store')->name('views.store');
    Route::delete('views/{viewId}', 'ViewController@destroy')->name('views.destroy');

    //routes per la gestione dei graphs
    Route::post('/viewGraphs/{viewId}', 'GraphsController@store')->name('graphs.store');
    Route::delete('/viewGraphs/{viewGraphId}', 'GraphsController@destroy')->name('graphs.destroy');

    //data
    Route::get('data/sensors/{sensorId}', 'SensorController@fetch')->name('sensors.fetch');
    Route::get('data/sensors', 'SensorController@fetchMoar')->name('sensors.fetchMoar');

    //dashboard
    Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::get('coffee', 'DashboardController@coffee')->name('dashboard.coffee');

    //routes per gestione profilo
    Route::get('settings/edit', 'SettingsController@edit')->name('settings.edit');
    Route::post('settings', 'SettingsController@updateAlerts')->name('settings.updateAlerts');
    Route::put('settings', 'SettingsController@update')->name('settings.update');

    //alert
    Route::get('/alerts', 'AlertsController@index')->name('alerts.index');

    //routes protette solo per admin e mod
    Route::middleware(['can:isAdmin' || 'can:isMod'])->group(function () {

        //routes per gestione user
        Route::get('users', 'UserController@index')->name('users.index');
        Route::get('users/create', 'UserController@create')->name('users.create');
        Route::post('users', 'UserController@store')->name('users.store');
        Route::get('users/{userId}', 'UserController@show')->name('users.show');
        Route::put('users/{userId}', 'UserController@update')->name('users.update');
        Route::get('users/{userId}/edit', 'UserController@edit')->name('users.edit');
        Route::put('users/{userId}/restore', 'UserController@restore')->name('users.restore');
        Route::put('users/{userId}/reset', 'UserController@reset')->name('users.reset');
        Route::delete('users/{userId}/delete', 'UserController@destroy')->name('users.destroy');

        //logs
        Route::get('logs', 'LogsController@index')->name('logs.index');

        //delete alert per admin
        Route::delete('alerts/{alertId}', 'AlertsController@destroy')->name('alerts.destroy');
    });

    //routes protette solo per mod
    Route::middleware('can:isMod')->group(function () {

        // Modifica, aggiunta, edit alerts
        Route::post('alerts', 'AlertsController@store')->name('alerts.store');
        Route::get('alerts/create', 'AlertsController@create')->name('alerts.create');
        Route::get('alerts/{alertId}', 'AlertsController@edit')->name('alerts.edit');
        Route::get('alerts/{alertId}', 'AlertsController@edit')->name('alerts.edit');
        Route::put('alerts/{alertId}', 'AlertsController@update')->name('alerts.update');
    });

    //routes protette solo per admin
    Route::middleware('can:isAdmin')->group(function () {

        //routes per gestione gateways
        Route::get('/gateways', 'GatewayController@index')->name('gateways.index');
        Route::post('/gateways', 'GatewayController@store')->name('gateways.store');
        Route::get('/gateways/create', 'GatewayController@create')->name('gateways.create');
        Route::get('/gateways/{gatewayId}/edit', 'GatewayController@edit')->name('gateways.edit');
        Route::put('/gateways/{gatewayId}/config', 'GatewayController@sendConfig')->name('gateways.config');
        Route::get('/gateways/{gatewayId}', 'GatewayController@show')->name('gateways.show');
        Route::put('/gateways/{gatewayId}', 'GatewayController@update')->name('gateways.update');
        Route::delete('/gateways/{gatewayId}', 'GatewayController@destroy')->name('gateways.destroy');

        //routes per gestione entity
        Route::get('/entities', 'EntityController@index')->name('entities.index');
        Route::post('/entities', 'EntityController@store')->name('entities.store');
        Route::get('/entities/create', 'EntityController@create')->name('entities.create');
        Route::get('/entities/{entityId}/edit', 'EntityController@edit')->name('entities.edit');
        Route::put('/entities/{entityId}/updateSensors', 'EntityController@updateSensors')
            ->name('entities.updateSensors');
        Route::get('/entities/{entityId}', 'EntityController@show')->name('entities.show');
        Route::put('/entities/{entityId}', 'EntityController@update')->name('entities.update');
        Route::delete('/entities/{entityId}', 'EntityController@destroy')->name('entities.destroy');

        //routes per gestione devices
        Route::post('/devices', 'DeviceController@store')->name('devices.store');
        Route::get('/devices/create', 'DeviceController@create')->name('devices.create');
        Route::get('/devices/{deviceId}/edit', 'DeviceController@edit')->name('devices.edit');
        Route::put('/devices/{deviceId}', 'DeviceController@update')->name('devices.update');
        Route::delete('/devices/{deviceId}', 'DeviceController@destroy')->name('devices.destroy');
    });

    //routes per gestione devices
    Route::get('/devices', 'DeviceController@index')->name('devices.index');
    Route::get('/devices/{deviceId}', 'DeviceController@show')->name('devices.show');

    //routes per gestione sensori
    Route::get('/devices/{deviceId}/sensors/{sensorId}', 'SensorController@show')->name('sensors.show');
});
