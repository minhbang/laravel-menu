<?php
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\Menu'],
    function () {
        Route::group(
            ['prefix' => 'menu', 'as' => 'backend.menu.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'Controller@data']);
                Route::get('{menu}/create', 'Controller@createChildOf');
                Route::post('move', ['as' => 'move', 'uses' => 'Controller@move']);
                Route::post('{menu}', ['as' => 'storeChildOf', 'uses' => 'Controller@storeChildOf']);
            }
        );
        Route::get('menu/for/{root}', ['as' => 'backend.menu.root', 'uses' => 'Controller@index']);
        Route::resource('menu', 'Controller');
    }
);