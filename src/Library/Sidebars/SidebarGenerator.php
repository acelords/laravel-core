<?php

namespace AceLords\Core\Library\Sidebars;

class SidebarGenerator
{
    /**
     * Get a sidebar according to role provided
     *
     * @param string $role
     *
     * @return array
     */
    public static function get(string $role) : array
    {
        $method = "for" . ucwords($role);
        
        if(method_exists(__CLASS__, $method)){
            return self::$method();
        }
        
        return [];
    }
    
    /**
     * return general sidebar entries for humans
     *
     * @return array
     */
    public static function forGeneral() : array
    {
        return self::readConfig('general');
    }
    
    /**
     * return sidebar entries for sudo
     *
     * @return array
     */
    public static function forSudo() : array
    {
        return self::readConfig('sudo');
    }
    
    /**
     * Read the sidebar data from config file
     *
     * @param string $key
     *
     * @return array
     */
    public static function readConfig(string $key)
    {
        $data = collect();
    
        foreach(getModules() as $module)
        {
            $name = strtolower($module->name);
        
            foreach($module->config_files as $file)
            {
                $filename = explode('.', $file)[0] ?? null;
                
                if(in_array($filename, ['sidebar']))
                {
                    $sidebar = config("{$name}.sidebar.{$key}");
                    $data->push($sidebar);
                }
            }
        }
    
        return $data->toArray();
    }
    
}
