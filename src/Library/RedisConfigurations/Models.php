<?php

namespace AceLords\Core\Library\RedisConfigurations;

use AceLords\Core\Library\Contracts\RedisInterface;
use Illuminate\Support\Facades\Schema;

class Models extends RedisTemplate implements RedisInterface
{

    /**
     * Set the keys as they are to be returned to redis
     */
    public function setKeys()
    {
        $this->keys = config('acelords_redis.models');
    }

    /**
     * Returns selects data to the repository for redis storage
     */
    public function data($key = null)
    {
        if(class_exists($key))
        {
            $model = new $key();
            $tableName = $model->getTable();

            if(Schema::hasTable("${key}")) {
                $data = $model->get()->toArray();

                return [
                    'key' => $tableName,
                    'data' => $data,
                ];
            }

            return [];
        }

        return collect();
    }

}