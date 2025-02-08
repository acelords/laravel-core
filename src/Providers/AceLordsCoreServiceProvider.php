<?php

namespace AceLords\Core\Providers;

use Illuminate\Support\ServiceProvider;
use AceLords\Core\Library\SiteConstants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Foundation\Http\Kernel;

class AceLordsCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel)
    {
        // $this->registerMiddlewares($kernel);
        // $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        // $this->registerFactories();
        // $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        // $this->registerLogChannels();

        $this->loadMacros();
        $this->defineAssetPublishing();

        $this->performAfterKernelFunctions();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMergeConfig();
        
        if (app()->environment('local')) {
            if (file_exists($file = __DIR__ . '/../helpers.php')) { 
                require $file;
            }
        }

        if (! defined('ACELORDS_CORE_PATH')) {
            define('ACELORDS_CORE_PATH', realpath(__DIR__.'/../../'));
        }
    }

    /**
     * publish config
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/redis.php' => config_path('acelords_redis.php'),
        ], 'acelords_redis');
        
        $this->publishes([
            __DIR__ . '/../Config/sidebar.php' => config_path('acelords_sidebar.php'),
        ], 'acelords_sidebar');
        
        $this->publishes([
            __DIR__ . '/../Config/logging.php' => config_path('acelords_logging.php'),
        ], 'acelords_logging');
        
    }

    /**
     * Register config to be merged
     *
     * @return void
     */
    protected function registerMergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/core.php', 'acelords_core'
        );
        
        $this->mergeConfigFrom(
            __DIR__.'/../Config/redis.php', 'acelords_redis'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../Config/logging.php', 'acelords_logging'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'acelordscore');

        // $this->publishes([
        //     __DIR__ . '/../Resources/views' => resource_path('views/vendor/acelords/core'),
        // ]);
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            ACELORDS_CORE_PATH . '/public' => public_path('vendor/acelords/core'),
        ], 'acelords-core-assets');
    }

    /**
     * add custom log channels
     */
    public function registerLogChannels()
    {
        if(isset($this->app['log'])) {
            $this->app['log']->stack([
                'channels' => config('acelords_logging.channels')
            ]);
        }
    }

    /**
     * register app macros
     * convert to collection this way:
     * $collection = collect($data)->recursive(); // $data is multidimensional array
     */
    public function loadMacros()
    {
        \Illuminate\Support\Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }

                return $value;
            });
        });
    }

    /**
     * perform functions after the kernel is handled
     */
    public function performAfterKernelFunctions()
    {
        if (class_exists(RequestHandled::class)) {
            Event::listen(RequestHandled::class, function(RequestHandled $event) {
                // set up site constants, eg theme
                new SiteConstants(); // this will set the theme automatically

                $this->performQueryLog();

            });
        } 
        else {
            Event::listen('kernel.handled', function($request, $response) {

                $this->performQueryLog();

            });
        }
    }

    /**
     * perform query log on DB actions
     */
    public function performQueryLog()
    {
        if(env("QUERY_LOG")) {
            if(request()->has('query-log')) {
                dd(DB::getQueryLog());
            }
        }
    }
}
