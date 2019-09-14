<?php

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

use Illuminate\Support\Facades\Route;

// Auth routes

Route::post('login', 'Auth\AuthController@login')->name('login');
Route::post('logout', 'Auth\AuthController@logout')->name('logout');
Route::post('refresh', 'Auth\AuthController@refresh')->name('refresh');
Route::post('current-user', 'Auth\AuthController@currentUser')->name('current-user');
Route::post('subscribe', 'Auth\AuthController@subscribe')->name('subscribe');

// Every route in this group require user authentication
Route::group(['middleware' => 'auth:api'], function () {

    // User resource routes
    Route::apiResource('users', 'UserController', ['parameters' => ['users' => 'user_id']])->except(['store']);
    Route::patch('users/{user_id}/terms-of-use', 'UserController@updateTermsOfUse')->name('users.update.terms-of-use');
    Route::get('users-promotion', 'UserController@indexPromotion')->name('users.index.promotion');
    Route::apiResource('users.applications', 'UserApplicationController', ['parameters' => ['users' => 'user_id']])
        ->only(['index', 'store']);

    // Role resource routes
    Route::get('roles', 'RoleController@index')->name('roles.index');

    // UserRole resource routes
    Route::apiResource('users.roles', 'UserRoleController', ['parameters' => [
        'users' => 'user_id',
        'roles' => 'role_id',
    ]])->only(['index', 'store']);

    // Preference resource routes
    Route::put('preferences/{preference_id}', 'PreferenceController@update')->name('preferences.update');

    // Client resource routes
    Route::apiResource('clients', 'ClientController', ['parameters' => ['clients' => 'client_id']]);
    Route::get('clients/{client_id}/projects', 'ClientProjectController@index')->name('clients.projects.index');
    Route::get('clients/{client_id}/conventions', 'ClientConventionController@index')->name('clients.conventions.index');
    Route::post('clients/{client_id}/conventions', 'ClientConventionController@store')->name('clients.conventions.store');

    // Convention resource routes
    Route::apiResource('conventions', 'ConventionController', ['parameters' => ['conventions' => 'convention_id']])
        ->only(['show', 'update', 'destroy']);

    // Contact resource routes
    Route::apiResource('contacts', 'ContactController', ['parameters' => ['contacts' => 'contact_id']]);

    // Project resource routes
    Route::apiResource('projects', 'ProjectController', ['parameters' => ['projects' => 'project_id']]);
    Route::get('projects-steps', 'ProjectController@indexStep')->name('projects.steps.index');
    Route::get('projects/{project_id}/missions', 'ProjectMissionController@index')->name('projects.missions.index');
    Route::apiResource('projects.users', 'ProjectUserController')
        ->only(['index', 'store', 'destroy']);
    Route::patch('projects/{project_id}/step', 'ProjectController@updateStep')->name('projects.update.step');
    Route::patch('projects/{project_id}/payment', 'ProjectController@updatePayment')->name('projects.update.payment');
    Route::put('projects/{project_id}/invoices', 'ProjectInvoiceController@update')->name('projects.invoices.update');

    // Mission resource routes
    Route::apiResource('missions', 'MissionController', ['parameters' => ['missions' => 'mission_id']]);
    Route::get('missions-available', 'MissionController@indexAvailable')->name('missions.index.available');
    Route::patch('missions/{mission_id}/lock', 'MissionController@updateLock')->name('missions.update.lock');
    Route::apiResource('missions.applications', 'MissionApplicationController', ['parameters' => ['missions' => 'mission_id']])
        ->only(['index', 'store']);
    Route::get('missions/{mission_id}/users', 'MissionUserController@indexNotApplied')->name('missions.users.index.not-applied');
    Route::put('missions/{mission_id}/bills', 'MissionBillController@update')->name('missions.bills.update');

    // Application resource routes
    Route::get('applications-statuses', 'ApplicationController@indexStatus')->name('applications.statuses.index');
    Route::apiResource('applications', 'ApplicationController', ['parameters' => ['applications' => 'application_id']])
        ->only(['update', 'destroy']);

    // Payslip resource routes
    Route::post('payslips', 'PayslipController@index')->name('payslips.index');
    Route::put('payslips', 'PayslipController@update')->name('payslips.update');

});
