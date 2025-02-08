<?php

namespace AceLords\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seos';

    protected $fillable = [
        'domain_id', 'page', 'title',
        'keywords', 'url', 'featured_img',
        'description',
    ];

    /**
     * Ensure the page is in lowercase when being saved
     *
     * @param $value
     */
    public function setPageAttribute($value)
    {
        $this->attributes['page'] = strtolower($value);
    }

    /**
     * an seo belongs to a domain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }
}
