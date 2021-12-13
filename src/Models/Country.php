<?php

namespace AceLords\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Country extends Model
{
    protected $table = "countries";
    
    protected $fillable = [
        'name', 'slug', 'iso_code', 'country_code',
        'region', 'sub_region', 'lat_lng', 'timezone_offset', 
        'currency_code', 'currency_name', 'currency_symbol',
    ];

    protected $casts = [
        'lat_lng' => 'array'
    ];
    
    public $timestamps = false;
    
    /**
     * Change the route model binding column
     */
    public function getRouteKeyName()
    {
        return "slug";
    }
    
    /**
     * Set the country's slug as you are setting the name
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value, "-");
    }

    /**
     * Get the timezone 
     * e.g. Africa/Nairobi
     */
    public function getTimezoneAttribute()
    {
        return $this->region . '/' . $this->subregion;
    }

}
