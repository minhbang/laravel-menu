<?php
Route::group(
    ['prefix' => 'backend', 'as' => 'backend.', 'namespace' => 'Minhbang\Menu', 'middleware' => config('menu.middleware')],
    function () {
        Route::group(
            ['prefix' => 'menu', 'as' => 'menu.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'Controller@data']);
                Route::get('{menu}/create', 'Controller@createChildOf');
                Route::post('move', ['as' => 'move', 'uses' => 'Controller@move']);
                Route::post('{menu}', ['as' => 'storeChildOf', 'uses' => 'Controller@storeChildOf']);
                Route::get('for/{name}', ['as' => 'name', 'uses' => 'Controller@index']);
            }
        );
        Route::resource('menu', 'Controller');
    }
);