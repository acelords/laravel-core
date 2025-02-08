<?php
/**
 * Created by PhpStorm.
 * User: lexxyungcarter
 * Date: 05/10/2019
 * Time: 15:14
 */

namespace AceLords\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class PackagesServiceProvider extends ServiceProvider
{
    /**
     * List of Package Providers
     * @var array
     */
    protected $packageProviders = [
        \Artesaos\SEOTools\Providers\SEOToolsServiceProvider::class,
    ];
    
    /**
     * List of only Local Environment Facade Aliases
     * @var array
     */
    protected $facadeAliases = [
        'Seo' => \Artesaos\SEOTools\Facades\SEOTools::class,
    ];
    
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerServiceProviders();
        $this->registerFacadeAliases();
    }
    
    /**
     * Load package service providers
     */
    protected function registerServiceProviders() {
        foreach ($this->packageProviders as $provider) {
            $this->app->register($provider);
        }
    }
    
    /**
     * Load additional Aliases
     */
    public function registerFacadeAliases() {
        $loader = AliasLoader::getInstance();
        foreach ($this->facadeAliases as $alias => $facade) {
            $loader->alias($alias, $facade);
        }
    }
}
