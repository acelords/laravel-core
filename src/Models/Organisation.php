<?php

namespace AceLords\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use SoftDeletes;

    protected $table = "organisations";

    protected $fillable = [
        "domain_id", "name", "country_id", "address", "town", "telephone", "mobile",
        "office", "street",  "building", "fax", "pin", "logo", "slogan", "bio", "theme_color",
        "social_profiles", "email", "no_reply_email", "support_email",
    ];

    protected $casts = [
        'social_profiles' => 'array',
    ];

    /**
     * an organisation belongs to a certain domain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    /**
     * an organization belongs to a certain country
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

}
