<?php
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\LaravelMenu'],
    function () {
        Route::group(
            ['prefix' => 'menu', 'as' => 'backend.menu.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'MenuController@data']);
                Route::get('{menu}/create', 'MenuController@createChildOf');
                Route::post('move', ['as' => 'move', 'uses' => 'MenuController@move']);
                Route::post('{menu}', ['as' => 'storeChildOf', 'uses' => 'MenuController@storeChildOf']);
            }
        );
        Route::get('menu/for/{root}', ['as' => 'backend.menu.root', 'uses' => 'MenuController@index']);
        Route::resource('menu', 'MenuController');
    }
);