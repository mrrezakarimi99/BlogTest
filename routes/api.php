<?php

Route::post('login', 'AuthController@login');

Route::middleware(['auth:api'])->group(function (){
    Route::delete('logout', 'AuthController@logout');
    Route::apiResource('post' , 'PostController');
});
