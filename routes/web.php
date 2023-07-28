<?php

$router->get('/', function(){ 
    return "Route GET Expense API"; 
});

$router->group(['prefix' => '/auth'], function() use ($router){
    $router->post('/', 'AuthLoginController@makelogin');
    $router->get('/',  'AuthLoginController@checkToken');
});

$router->group(['prefix' => '/expenses', 'middleware' => ['authcheck']], function() use ($router){
    $router->get('/', 'ExpenseController@getAll');
    $router->get('/{id}', 'ExpenseController@getOne');
});