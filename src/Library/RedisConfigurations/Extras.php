<?php

namespace AceLords\Core\Library\RedisConfigurations;

use Illuminate\Support\Facades\DB;
use AceLords\Core\Library\Contracts\RedisInterface;
use Illuminate\Support\Facades\Schema;

class Extras extends RedisTemplate implements RedisInterface
{

    /**
     * Set the keys as they are to be returned to redis
     */
    public function setKeys()
    {
        $this->keys = config('acelords_redis.extras');
    }

    /**
     * Returns selects data to the repository for redis storage
     */
    public function data($key = null)
    {
        $key = str_replace(config('acelords_redis.application_prefix'), '', $key);

        if(Schema::hasTable("${key}")) {
            return DB::table("${key}")->get();
        }

        return collect();
    }
}