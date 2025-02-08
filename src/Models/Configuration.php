<?php

namespace AceLords\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = "configurations";

    protected $fillable = [
        "name", "value", "module", "view", "hint", "default", "description"
    ];

    /**
     * constructor
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('acelords_redis.defaults.tables.configurations');
        
        parent::__construct($attributes);
    }

}
