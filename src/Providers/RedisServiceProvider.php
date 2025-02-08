<?php

namespace AceLords\Core\Providers;

use Illuminate\Support\ServiceProvider;
use AceLords\Core\Repositories\RedisRepository;
use AceLords\Core\Library\RedisConfigurations\Extras;
use AceLords\Core\Library\RedisConfigurations\Models;
use AceLords\Core\Library\RedisConfigurations\Sidebar;
use AceLords\Core\Library\RedisConfigurations\Settings;

class RedisServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $redis = resolve(RedisRepository::class);

        $this->setRedisExtras($redis);

        $this->setRedisModels($redis);

        // $this->registerSidebar($redis);

        $this->registerSettings($redis);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(RedisRepository::class, function () {
        //     return new RedisRepository();
        // });
    }

    /**
     * For seed classes that need to exist in redis and accessed also as selects within the UI
     */
    public function setRedisExtras($redis)
    {
        $select = new Extras();

        foreach(config('acelords_redis.extras') as $key)
        {
            if(is_array($key)) {
                $key = $key['table'];
            }

            if(! $redis->exists($key))
                $redis->store($select, $key);
        }
    }

    /**
     * For seed classes that need to exist in redis and need to be fetched as models
     */
    public function setRedisModels($redis)
    {
        $select = new Models();

        foreach(config('acelords_redis.models') as $key)
        {
            if(class_exists($key))
            {
                // $model = new $key();
                // $key = $model->getTable();
                
                if(! $redis->exists($key))
                    $redis->store($select, $key);
            }
        }
    }

    /**
     * Register the general system sidebar.
     * Exist in modules config/settings
     */
    public function registerSidebar($redis)
    {
        $sidebar = new Sidebar();

        if(! $redis->exists('sidebar'))
            $redis->store($sidebar, 'sidebar');

        if(! $redis->exists('sudo_sidebar'))
            $redis->store($sidebar, 'sudo_sidebar');
    }

    /**
     * Register the general system settings.
     * Exist in modules config/settings
     */
    public function registerSettings($redis)
    {
        $settings = new Settings();
        
        if(! $redis->exists('settings'))
        {
            $redis->store($settings, 'settings');
        }
        
        if(!$redis->exists('configurations'))
        {
            $redis->store($settings, 'configurations');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}