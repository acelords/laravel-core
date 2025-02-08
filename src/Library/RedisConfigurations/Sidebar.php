<?php

namespace AceLords\Core\Library\RedisConfigurations;

use AceLords\Core\Library\Contracts\RedisInterface;

class Sidebar extends RedisTemplate implements RedisInterface
{
    /**
     * Set the keys as they are to be returned to redis
     */
    public function setKeys()
    {
        $this->keys = ['sidebar', 'sudo_sidebar'];
    }

    /**
     * Returns selects data to the repository for redis storage
     */
    public function data($key = null)
    {
        if($key == "sidebar") {
            return $this->getSidebar('general');
        }

        return $this->getSidebar('sudo');
    }

    /**
     * get the sudo sidebar entries
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getSidebar(string $key) : array
    {
        $data  = collect();
        $sidebarClass = config("acelords_sidebar.class");
        
        if(! class_exists($sidebarClass))
            throw new \Exception("Sidebar Class not found!");
        
        $class = new $sidebarClass();
    
        foreach($class::get($key) as $config)
        {
            $data->push(collect($config)->recursive());
        }

        return $data->toArray();
    }
}