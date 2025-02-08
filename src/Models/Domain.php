<?php

namespace AceLords\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    protected $table = 'domains';

    protected $fillable = [
        'slug', 'name', 'url',
    ];

    /**
     * a domain can have only one theme
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function theme()
    {
        return $this->hasOne(Theme::class, 'domain_id');
    }

    /**
     * a domain has one organisation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function organisation()
    {
        return $this->hasOne(Organisation::class, 'domain_id');
    }

    /**
     * a domain has one seo page setting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function seo()
    {
        return $this->hasOne(Seo::class, 'domain_id');
    }
}
