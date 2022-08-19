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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/user/register', function () use ($router) {
        return 'register';
    });

    $router->post('/user/sign-in', ['uses' => 'UserController@login']);

    $router->post('/user/recover-password', []
//    method POST/PATCH
//    fields: email [string] // allow to update the password via email token
    );

    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->get('/user/companies/', ['uses' => 'CompanyController@showAllCompanies']);

        $router->post('/user/companies', ['uses' => 'CompanyController@create']
    //    fields: title [string], phone [string], description [string]
    //    add the companies, associated with the user (by the relation)
        );
    });
});







