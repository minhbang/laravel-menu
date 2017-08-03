<?php

namespace Minhbang\Menu;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use MenuManager;

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
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'menu');
        $this->loadViewsFrom(__DIR__.'/../views', 'menu');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/../views' => base_path('resources/views/vendor/menu'),
            __DIR__.'/../lang' => base_path('resources/lang/vendor/menu'),
            __DIR__.'/../config/menu.php' => config_path('menu.php'),
        ]);
        // pattern filters
        $router->pattern('menu', '[0-9]+');
        // model bindings
        $router->model('menu', Menu::class);

        MenuManager::registerMenuTypes(config('menu.types'));

        // Add menus
        MenuManager::addItems(config('menu.menus'));
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/menu.php', 'menu');
        $this->app->singleton('menu-manager', function () {
            return new Manager(config('menu.actives'), config('menu.presenters'), config('menu.settings'));
        });
        // add Setting alias
        $this->app->booting(function () {
            AliasLoader::getInstance()->alias('MenuManager', Facade::class);
        });
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
