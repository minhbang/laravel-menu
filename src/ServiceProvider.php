<?php

namespace Minhbang\Menu;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Minhbang\Kit\Extensions\BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package Minhbang\Menu
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'menu');
        $this->loadViewsFrom(__DIR__ . '/../views', 'menu');
        $this->publishes(
            [
                __DIR__ . '/../views'           => base_path('resources/views/vendor/menu'),
                __DIR__ . '/../lang'            => base_path('resources/lang/vendor/menu'),
                __DIR__ . '/../config/menu.php' => config_path('menu.php'),
            ]
        );
        $this->publishes(
            [
                __DIR__ . '/../database/migrations/2015_03_21_155451_create_menus_table.php' =>
                    database_path('migrations/2015_03_21_155451_create_menus_table.php'),
            ],
            'db'
        );
        
        $this->mapWebRoutes($router, __DIR__ . '/routes.php', config('menu.add_route'));
        
        // pattern filters
        $router->pattern('menu', '[0-9]+');
        // model bindings
        $router->model('menu', Menu::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/menu.php', 'menu');
        $this->app['menu-manager'] = $this->app->share(
            function () {
                return new Manager(
                    config('menu.actives'),
                    config('menu.presenters'),
                    config('menu.types'),
                    config('menu.settings')
                );
            }
        );
        // add Setting alias
        $this->app->booting(
            function () {
                AliasLoader::getInstance()->alias('MenuManager', Facade::class);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['menu-manager'];
    }
}