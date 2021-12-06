<?php

namespace AceLords\Core\Library\RedisConfigurations;

use AceLords\Core\Library\Contracts\RedisInterface;
use AceLords\Core\Models\Configuration;
use Illuminate\Support\Facades\Schema;

class Settings extends RedisTemplate implements RedisInterface
{
    protected $data = [];
    protected $configModel;

    /**
     * Set the keys as they are to be returned to redis
     */
    public function setKeys()
    {
        $this->keys = ['settings', 'configurations'];
    }

    /** 
     * Returns sidebar data that should be put into redis
     * Data is fetched from modules config/settings.php
     */
    public function data($key = null)
    {
        if($key == "configurations")
        {
            return $this->configurations();
        }

        return $this->settings();
    }

    /**
     * Redis settings exist in two different states
     * These are the overall settings defined
     * In the modules config/settings.php
     */
    public function settings()
    {
        foreach(getModules() as $module)
        {
            $moduleName = strtolower($module->name);
        
            $this->data[$moduleName] = (Array) config($moduleName . '.settings');
        }
        
        return array_filter($this->data);
    }

    /**
    * Redis settings exist in two different states
    * These are the organization settings configured
    * By the user from within the application
    * Always Clear defaults in DB first
    */
    public function configurations()
    {
        $table = (new Configuration())->getTable();

        if(! Schema::hasTable("${$table}")) {
            return collect();
        }

        $data = $this->settings();

        $this->setDefaultConfigurations($data);

        return Configuration::all();
    }

    /**
     * get the configuration model
     */
    public function getModel()
    {
        $class = config("acelords_redis.defaults.models.configuration");
        if(! class_exists($class))
            throw new \Exception("{$class} does not exist!");
        
        return new $class();
    }

    /**
     * Set the default configurations/organization settings given general settings data
     */
    public function setDefaultConfigurations($data)
    {
        foreach($data as $module => $value)
        {
            foreach($value as $name => $config)
            {
                if(! Configuration::where('name', $name)->first())
                {
                    $this->getModel()->create([
                        'name'          => $name,
                        'value'         => $config['default'] ?? null,
                        'module'        => $module,
                        'view'          => $config['view'],
                        'hint'          => $config['hint'] ?? null,
                        'description'   => $config['description'],
                    ]);
                }
            }
        }
    }

    /**
     * clear settings and revert to default
     */
    public function resetAllConfigurationSettings()
    {
        $this->getModel()->query()->truncate();

        return $this->configurations();
    }

    /**
     * truncate config table
     */
    public function truncateTable()
    {
        Configuration::truncate();
    }
    
}
