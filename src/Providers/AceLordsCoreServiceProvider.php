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
        // set up site constants, eg theme
        new SiteConstants(); // this will set the theme automatically

        // share data to views
        // view()->share($siteConstants->data());

        // $this->registerMiddlewares($kernel);
        // $this->registerTranslations();
        // $this->registerConfig();
        // $this->registerViews();
        // $this->registerFactories();
        // $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerLogChannels();

        $this->loadMacros();

        $this->performQueryLog();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMergeConfig();
        
        $this->registerCommands(); 
        
        if (app()->environment('local')) {
            if (file_exists($file = __DIR__ . '/../helpers.php')) { 
                require $file;
            }
        }
    }

    /**
     * Register config to be merged
     *
     * @return void
     */
    protected function registerMergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'./../Config/core.php', 'acelords_core'
        );
        
        $this->mergeConfigFrom(
            __DIR__.'./../Config/redis.php', 'acelords_redis'
        );

        $this->mergeConfigFrom(
            __DIR__.'./../Config/logging.php', 'acelords_logging'
        );
    }

    /**
     * register app commands
     */
    public function registerCommands()
    {
        $this->commands([
            \AceLords\Core\Commands\GenerateProductKey::class,
            \AceLords\Core\Commands\TestQueue::class,
        ]);
    }

    /**
     * add custom log channels
     */
    public function registerLogChannels()
    {
        $this->app['log']->stack([
            'channels' => config('acelords_logging.channels')
        ]);
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

    public function performQueryLog()
    {
        if(env("QUERY_LOG"))
        {
            DB::enableQueryLog();
    
            if (class_exists(RequestHandled::class)) {
                Event::listen(RequestHandled::class, function(RequestHandled $event) {
                    if(request()->has('query-log'))
                    {
                        dd(DB::getQueryLog());
                    }
                });
            } 
            else {
                Event::listen('kernel.handled', function($request, $response) {
                    if($request->has('query-log'))
                    {
                        dd(DB::getQueryLog());
                    }
                });
            }
        }
    }
}