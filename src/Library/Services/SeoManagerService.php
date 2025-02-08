<?php
/**
 * Created by PhpStorm.
 * User: lexxyungcarter
 * Date: 05/10/2019
 * Time: 15:17
 */

namespace AceLords\Core\Library\Services;

use AceLords\Core\Library\SiteConstants;
use Seo;

class SeoManagerService
{
    /**
     * hold important variables for the class
     *
     * @var string
     */
    protected $siteName, $siteLogo, $title, $description, $imageUrl, $pageType, $keywords, $email, $mobile, $showSeo = true;
    
    /**
     * Hold social site settings
     *
     * @var string
     */
    protected $twitterUsername, $facebookUrl, $fbAppId = null;
    
    /**
     * Hold redis seo config index/key
     * @var mixed
     */
    protected $redisKey;
    
    /**
     * get our site constants
     * @var array
     */
    protected $siteConstants;
    
    /**
     * Constructor
     *
     * @param string $pageTitle
     * @param string $pageDescription
     * @param string $imageUrl
     * @param string $pageType
     * @param string $keywords
     */
    public function __construct(
        $pageTitle = 'AceLords Systems',
        $pageDescription = 'AceLords Web & Mobile Systems',
        $imageUrl = '/img/screenshot.jpg',
        $pageType = 'page',
        $keywords = "System Development Services"
    )
    {
        $this->title = $pageTitle;
        $this->description = $pageDescription;
        $this->pageType = $pageType;
        $this->keywords = $keywords;
        $this->imageUrl = $imageUrl;
        
        $this->redisKey = 'seos';
        $this->siteConstants = (new SiteConstants())->data();
        
        $this->siteName = $this->siteConstants['site_name'] ?? null;
        $this->siteLogo = $this->siteConstants['site_logo'] ?? null;
    }
    
    /**
     * Get the seos for the page
     *
     * @param string $page
     *
     * @return $this
     */
    public function page(string $page)
    {
        $redis = redis();
        $page = strtolower($page); // since mysql^5 is case-sensitive
        $site_domain = $this->siteConstants['site_domain'] ?? false;
        
        if(! $site_domain) {
            return $this;
        }
        
        $seoDetails = $redis->get($this->redisKey)->where('page', $page)->first();
        
        if(! $seoDetails) {
            $this->setTitle(ucwords(str_replace('-', ' ', $page)));
            
            return $this;
        }
        
        // set new things
        $this->title = $seoDetails->title;
        $this->description = $seoDetails->description;
        $this->keywords = $seoDetails->keywords;
        $this->twitterUsername = $this->siteConstants['site_twitter'] ?? null;
        $this->email = $this->siteConstants['site_email'] ?? null;
        $this->mobile = $this->siteConstants['site_mobile'] ?? null;
        
        if($seoDetails->featured_img)
            $this->imageUrl = '/img/seos/' . $seoDetails->featured_img;
        
        return $this;
    }
    
    
    /**
     * Set title for the SEO
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Set keywords for the SEO
     *
     * @param string $text
     *
     * @return $this
     */
    public function setKeywords(string $text)
    {
        $this->keywords = $text;
        return $this;
    }
    
    /**
     * Set entity for the SEO
     *
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    public function setEntity(string $attribute, string $value)
    {
        if(isset($this->$attribute))
            $this->$attribute = $value;
        
        return $this;
    }
    
    /**
     * change the pageType for SEO
     * eg. page, article, post
     *
     * @param string $pageType
     *
     * @return $this
     */
    public function setPageType(string $pageType)
    {
        $this->pageType = $pageType;
        return $this;
    }
    
    /**
     * set whether to show seo or not
     *
     * @param bool $status
     */
    public function setShowSeo(bool $status)
    {
        $this->showSeo = $status;
    }
    
    /**
     * generate SEO content according to page.
     *
     * @return array $seos
     */
    public function generate() : array
    {
        return [
            Seo::setTitle($this->title),
            Seo::setDescription($this->description),
            Seo::opengraph()->setUrl(request()->root()),
            Seo::setCanonical(url()->current()),
            Seo::opengraph()->addProperty('type', $this->pageType),
            Seo::opengraph()->addProperty('keywords', $this->keywords),
            Seo::twitter()->setSite('@' . $this->twitterUsername),
            Seo::jsonLd()->addImage($this->imageUrl),
            Seo::opengraph()->addProperty('logo-url', $this->siteLogo),
            Seo::opengraph()->addProperty('email', $this->email),
            Seo::opengraph()->addProperty('phone', $this->mobile),

            // Seo::set('breadcrumblist', [
            //     ['title' => 'Home', 'url' => request()->root()],
            //     ['title' => $this->title, 'url' => url()->current()]
            // ]),
            // Seo::setContactPoint([
            //     'type' => 'customer-service',
            //     'phone' => $this->get_option($s, "site_phone"),
            //     'area-served' => 'Kenya',
            //     'opening-hours' => 'Mo, Tu, We, Th, Fr, Sa 08:00-17:00',
            //     'available-languages' => ['English', 'Swahili']
            // ])
        ];
    }
    
    /**
     * get seo data in array
     *
     * @return array
     */
    public function data() : array
    {
        return [
            'global-description' => $this->description,
            'global-title' => $this->title,
            'title' => $this->title,
            'description' => $this->description,
            'og-title' => $this->title,
            'og-type' => $this->pageType,
            'keywords' => $this->keywords,
            'og-locale' => 'en-US',
            'canonical-url' => url()->current(),
            'og-image-url' => $this->imageUrl,
            'og-site-name' => $this->siteName,
            'logo-url' => $this->siteLogo,
            'facebook-url' => $this->facebookUrl,
            'set-similar-to' => [$this->facebookUrl, 'https://twitter.com/' . $this->twitterUsername],
            'twitter-sign' => '@' . $this->twitterUsername,
            'fb-app-id' => $this->fbAppId,
            'email' => $this->email,
            'phone' => $this->mobile,
            'breadcrumblist' => [
                ['title' => 'Home', 'url' => request()->root()],
                ['title' => $this->title, 'url' => url()->current()]
            ],
        ];
    }
    
    /**
     * access object properties
     *
     * @param $name
     *
     * @return null
     */
    public function  __get($name) {
        if(isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }
}
