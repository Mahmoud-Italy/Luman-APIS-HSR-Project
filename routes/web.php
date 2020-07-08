<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->get('/', function () use ($router) {
    return $router->app->version();
});

# API v1 created at July, 2020
$router->group(['prefix' => 'api/v1'], function($router){

    /** Backend **/
    $router->group(['prefix' => 'dashboard', 'namespace' => 'backend'], function($router){

        # Auth
        $router->group(['prefix' => 'auth'], function ($router) {
            $router->post('register', 'AuthController@register');
            $router->post('login', 'AuthController@login');
            $router->post('logout', 'AuthController@logout');
            $router->post('refresh', 'AuthController@refresh');
            $router->post('me', 'AuthController@me');
        });

        # Reports
        $router->get('reports', 'ReportController@index');
        $router->post('reports', 'ReportController@store');
        $router->get('reports/{id}', 'ReportController@show');
        $router->patch('reports/{id}', 'ReportController@update');
        $router->delete('reports/{id}', 'ReportController@destroy');

        # Projects
        $router->get('projects', 'ProjectController@index');
        $router->post('projects', 'ProjectController@store');
        $router->get('projects/{id}', 'ProjectController@show');
        $router->patch('projects/{id}', 'ProjectController@update');
        $router->delete('projects/{id}', 'ProjectController@destroy');

        # Categories
        $router->get('categories', 'CategoryController@index');
        $router->post('categories', 'CategoryController@store');
        $router->get('categories/{id}', 'CategoryController@show');
        $router->patch('categories/{id}', 'CategoryController@update');
        $router->delete('categories/{id}', 'CategoryController@destroy');

        # Tags
        $router->get('tags', 'TagController@index');
        $router->post('tags', 'TagController@store');
        $router->get('tags/{id}', 'TagController@show');
        $router->patch('tags/{id}', 'TagController@update');
        $router->delete('tags/{id}', 'TagController@destroy');

        # Accounts
        $router->get('accounts', 'AccountController@index');
        $router->post('accounts', 'AccountController@store');
        $router->get('accounts/{id}', 'AccountController@show');
        $router->patch('accounts/{id}', 'AccountController@update');
        $router->delete('accounts/{id}', 'AccountController@destroy');

        # Inbox
        $router->get('inbox', 'InboxController@index');
        $router->get('inbox/{id}', 'InboxController@show');
        $router->delete('inbox/{id}', 'InboxController@destroy');

        # Orders
        $router->get('orders', 'OrderController@index');
        $router->get('orders/{id}', 'OrderController@show');
        $router->patch('orders/{id}', 'OrderController@update');
        $router->delete('orders/{id}', 'OrderController@destroy');


        # Settings

            # App
            $router->get('settings', 'SettingController@index');

            # Countries
            $router->get('countries', 'CountryController@index');
            $router->post('countries', 'CountryController@store');
            $router->patch('countries/{id}', 'CountryController@update');
            $router->delete('countries/{id}', 'CountryController@destroy');

            # Sliders
            $router->get('sliders', 'SliderController@index');
            $router->post('sliders', 'SliderController@store');
            $router->get('sliders/{id}', 'SliderController@show');
            $router->patch('sliders/{id}', 'SliderController@update');
            $router->delete('sliders/{id}', 'SliderController@destroy');

            # Socials
            $router->get('socials', 'SocialController@index');
            $router->post('socials', 'SocialController@store');
            $router->get('socials/{id}', 'SocialController@show');
            $router->patch('socials/{id}', 'SocialController@update');
            $router->delete('socials/{id}', 'SocialController@destroy');

            # Roles
            $router->get('roles', 'RoleController@index');
            $router->post('roles', 'RoleController@store');
            $router->patch('roles/{id}', 'RoleController@update');
            $router->delete('roles/{id}', 'RoleController@destroy');

            # Pages
            $router->get('pages', 'PageController@index');
            $router->post('pages', 'PageController@store');
            $router->get('pages/{id}', 'PageController@show');
            $router->patch('pages/{id}', 'PageController@update');
            $router->delete('pages/{id}', 'PageController@destroy');
            
            # Permissions
            $router->get('permissions', 'PermissionController@index');
            $router->post('permissions', 'PermissionController@store');
            $router->get('permissions/{id}', 'PermissionController@show');
            $router->patch('permissions/{id}', 'PermissionController@update');
            $router->delete('permissions/{id}', 'PermissionController@destroy');


        # Staff
        $router->get('staffs', 'StaffController@index');
        $router->post('staffs', 'StaffController@store');
        $router->get('staffs/{id}', 'StaffController@show');
        $router->patch('staffs/{id}', 'StaffController@update');
        $router->delete('staffs/{id}', 'StaffController@destroy');
    });







    /** Frontend **/
    $router->group(['namespace' => 'frontend'], function($router){

        # Auth
        $router->group(['prefix' => 'auth'], function ($router) {
            $router->post('register', 'AuthController@register');
            $router->post('login', 'AuthController@login');
            $router->post('logout', 'AuthController@logout');
            $router->post('refresh', 'AuthController@refresh');
            $router->post('me', 'AuthController@me');
        });

        # Cache Clear
        $router->get('cache/clear', function(){
            Cache::flush();
        });

    });

});

