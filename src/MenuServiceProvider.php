<?php

namespace Minhbang\LaravelMenu;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'menu');
        $this->loadViewsFrom(__DIR__ . '/../views', 'menu');
        $this->publishes(
            [
                __DIR__ . '/../views'                      => base_path('resources/views/vendor/menu'),
                __DIR__ . '/../lang'                       => base_path('resources/lang/vendor/menu'),
                __DIR__ . '/../config/menu.php'            => config_path('menu.php'),
                __DIR__ . '/../database/migrations/' .
                '2015_03_21_155451_create_menus_table.php' =>
                    database_path('migrations/' . '2015_03_21_155451_create_menus_table.php'),
            ]
        );

        if (config('menu.add_route') && !$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }
        // pattern filters
        $router->pattern('menu', '[0-9]+');
        // model bindings
        $router->model('menu', 'Minhbang\LaravelMenu\MenuItem');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/menu.php', 'menu');
        $this->app['menu'] = $this->app->share(
            function ($app) {
                $factory = config('menu.factory');
                return new Menu(
                    config('menu.actives'),
                    new $factory(),
                    config('menu.presenters')
                );
            }
        );
        // add Setting alias
        $this->app->booting(
            function ($app) {
                AliasLoader::getInstance()->alias('Menu', MenuFacade::class);
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
        return ['menu'];
    }
}