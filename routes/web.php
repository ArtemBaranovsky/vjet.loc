<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/user/reset-password', [
    'as' => 'password.reset',
    'uses' => 'Auth\ResetPasswordController@reset'
]);

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/user/register', 'Auth\RegisterController@register');

    $router->post('/user/sign-in', 'Auth\LoginController@login');

    $router->post('/user/recover-password', [
        'as' => 'password.update',
        'uses' => 'Auth\RequestPasswordController@sendResetLink'
        ]
    );

    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->get('/user/companies/', 'CompanyController@showAllCompanies');

        $router->post('/user/companies', 'CompanyController@create'
        );
    });
});







