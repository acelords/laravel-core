<?php

namespace AceLords\Core\Library;

use Illuminate\Support\Facades\Cache;
use AceLords\Core\Repositories\RedisRepository;
use Illuminate\Support\Carbon;

class SiteConstants
{
    protected $repo, $data;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->repo = resolve(RedisRepository::class);

        $this->data = collect();

        $this->getTargetDomain();

        $this->getOrganisationDetails();

        $this->getTheme();

        $this->extra();

        $this->saveToSession();
    }

    /**
     * return an array to be shared to the views
     */
    public function data()
    {
        return $this->data->toArray();
    }

    /**
     * get the target domain details
     */
    public function getTargetDomain()
    {
        // get domain first.. from redis
        $domains = $this->repo->get('domains');

        if($domains) {
            $target = $domains->where('url', sanitizeDomainUrl(request()->url()))->first();

            if($target) {
                $this->data->put('site_domain', $target);
            }
        }
    }

    /**
     * get the organisation manenos
     */
    public function getOrganisationDetails()
    {
        // site details manenos
        if ($this->data->get('site_domain'))
        {
            $orgDetails = $this->repo->get('organisations');
            
            if($orgDetails)
            {
                $orgDetails = $this->repo->get('organisations')
                    ->where('domain_id', $this->data->get('site_domain')->id)
                    ->first();
            }

            if ($orgDetails)
            {
                // append common org details
                collect((array)($orgDetails))
                    ->except(['id', 'domain_id', 'created_at', 'updated_at', 'deleted_at', 'social_profiles'])
                    ->each(function($item, $key) {
                        $this->data->put('site_' .$key, $item);
                    });

                // append social sites
                $socialProfiles = collect(json_decode(collect((array)($orgDetails))->get('social_profiles')));
                if($socialProfiles && count($socialProfiles) > 0)
                {
                    $socialProfiles->each(function($item, $key) {
                        $this->data->put('site_' .$key, $item);
                    });
                } else {
                    $this->setNotFoundMediaProfile();
                }

                // append country name
                $countryId = $this->data->get('site_country_id');
                if($countryId) {
                    $country = $this->repo->get('countries')
                        ->where('id', $countryId)
                        ->first();

                    if($country) {
                        $this->data->put('site_country', $country->name);
                    } else {
                        $this->data->put('site_country', null);
                    }
                } else {
                    $this->data->put('site_country', null);
                }

            } else {
                $this->setNotFoundOrganization();
            }
        } else {
            $this->setNotFoundOrganization();
        }

        $this->setNotFoundMediaProfile();
    }

    /**
     * Set the theme to be used in front-end
     */
    public function getTheme()
    {
        $targetTheme = env('ACELORDS_FRONTEND_THEME');

        if ($this->data->get('site_domain'))
        {
            $theme = $this->repo->get('themes')
                ->where('domain_id', $this->data->get('site_domain')->id)
                ->first();

            if ($theme)
            {
                $targetTheme = $theme->name;
            }
        }

        $this->data->put(config('acelords_core.site_theme_key'), $targetTheme);

        // feed current data into the session. No need to add other info
        session()->put(config('acelords_core.site_theme_key'), $targetTheme);
    }

    /**
     * Attach extra items
     */
    public function extra()
    {
        $configurations = $this->repo->get('configurations');

        if(! $configurations)
            $configurations = collect();

        $this->data->put('title_separator', $configurations->firstWhere('name', 'title_separator')->value ?? '-');
        $this->data->put('site_version', $configurations->firstWhere('name', 'site_version')->value ?? '-');
        $this->data->put('site_codename', $configurations->firstWhere('name', 'site_codename')->value ?? '-');
        $this->data->put('product_name', $configurations->firstWhere('name', 'product_name')->value ?? '-');
        $this->data->put('system_updates_endpoint', $configurations->firstWhere('name', 'system_updates_endpoint')->value ?? '-');
    }

    /**
     * set up some stuff in session
     */
    private function saveToSession()
    {
        session()->put('site_name', $this->data->get('site_name'));
        session()->put('site_email', $this->data->get('site_email'));
        session()->put('site_support_email', $this->data->get('site_support_email'));
        session()->put('site_no_reply_email', $this->data->get('site_no_reply_email'));
        session()->put('site_mobile', $this->data->get('site_mobile'));
        session()->put('site_telephone', $this->data->get('site_telephone'));
        session()->put('site_address', $this->data->get('site_address'));
        session()->put('site_theme_color', $this->data->get('site_theme_color'));
        session()->put('site_version', $this->data->get('site_version'));
        session()->put('site_codename', $this->data->get('site_codename'));
        session()->put('product_name', $this->data->get('product_name'));
    }

    /**
     * Pre-fill entries if entries are not yet set
     */
    private function setNotFoundOrganization()
    {
        $this->data->put('site_name', env('APP_NAME'));
        $this->data->put('site_country', null);
        $this->data->put('site_address', null);
        $this->data->put('site_telephone', null);
        $this->data->put('site_mobile', null);
        $this->data->put('site_email', null);
        $this->data->put('site_support_email', null);
        $this->data->put('site_no_reply_email', null);
        $this->data->put('site_office', null);
        $this->data->put('site_street', null);
        $this->data->put('site_town', null);
        $this->data->put('site_building', null);
        $this->data->put('site_fax', null);
        $this->data->put('site_pin', null);
        $this->data->put('site_logo', null);
        $this->data->put('site_slogan', null);
        $this->data->put('site_bio', null);
        $this->data->put('site_theme_color', null);
        $this->data->put('site_version', null);
        $this->data->put('product_name', null);
        $this->data->put('site_codename', null);
    }

    /**
     * set profile
     */
    private function setNotFoundMediaProfile()
    {
        // social networks
        $networks = [
            'facebook', 'twitter', 'github', 'pinterest', 'discord',
            'whatsapp', 'telegram', 'instagram', 'dribble', 'linkedin',
            'youtube', 'patreon', 'buymecoffee',
        ];

        foreach($networks as $n) {
            if(! $this->data->get("site_{$n}"))
                $this->data->put("site_{$n}", '#');
        }
    }

}
