<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use AceLords\Core\Repositories\RedisRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;


if(!function_exists('debugOn'))
{
    /**
     * Check if debug mode is activated
     */
    function debugOn()
    {
        return env('APP_DEBUG');
    }
}

if (! function_exists('adjustBrightness'))
{
    function adjustBrightness($hex, $steps) 
    { 
        // Steps should be between -255 and 255. Negative = darker, positive = lighter 
        $steps = max(-255, min(255, $steps)); 
        // Normalize into a six character long hex string 
        $hex = str_replace('#', '', $hex); 
        if (strlen($hex) == 3) { 
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2); 
        } 
        // Split into three parts: R, G and B 
        $color_parts = str_split($hex, 2); 
        $return = '#';
        foreach ($color_parts as $color) { 
            $color = hexdec($color); // Convert to decimal 
            $color = max(0,min(255,$color + $steps)); // Adjust color 
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code 
        } 
        return $return;
    }
}

if (! function_exists('core_paginate'))
{
    /**
     * Get paginations for different parts of the System.
     * Opted for function in case the paginations require to be
     * retrieved via a setting instead of config option.
     * If an integer is provided, its value is returned.
     * 'xs' => 5,
     * 's' => 10,
     * 'm' => 25,
     * 'l' => 50,
     * 'xl' => 100,
     * 'p' => 7,
     * 'xxl' => 500
     * 'xxxl' => 1000
     *
     * @param string $entity
     * @return int $pagination
     */
    function core_paginate($entity = "p") {
        $paginations = config('acelords_core.pagination');
        foreach($paginations as $key => $value) {
            if ($key == strtolower($entity))
                return (int)$value;
        }
        return is_numeric($entity) ? $entity : (int)$paginations['p'];
    }
}

if (! function_exists('dde'))
{
    /**
     * add to the default dd()
     * to return 500 response code for ajax error detection
     */
    function dde(...$vars)
    {
        http_response_code(500);

        foreach ($vars as $v) {
            \Symfony\Component\VarDumper\VarDumper::dump($v);
        }

        die(1);
    }
}

if (! function_exists('doe'))
{
    /**
     * Returns the logged in user w.r.t. the request
     */
    function doe()
    {
        if(request()->ajax())
        {
            return auth()->guard('api')->user();
        }

        return auth()->user();
    }
}

if (! function_exists("eclair"))
{
    /**
     * Prepares a date for a more user ready format
     */
    function eclair($date, $time = true, $toW3cString = false)
    {
        if(!$date)
            return null;

        if($toW3cString)
            return Carbon::parse($date)->toW3cString();

        if($time) {
            return Carbon::parse($date)->format("M d, Y h:i:s a");
        }

        return Carbon::parse($date)->format("M d, Y");
    }
}

if (! function_exists('sanitizeDomainUrl'))
{
    /**
     * Sanitize URL
     *
     * @var string
     * @return string
     */
    function sanitizeDomainUrl(string $str = "") : string
    {
        empty($str) ? $str = request()->root() : null;

        // $input = 'www.google.co.uk/';
        // in case scheme relative URI is passed, e.g., //www.google.com/
        $str = trim($str, '/');

        // If scheme not included, prepend it
        if (! preg_match('#^http(s)?://#', $str)) {
            $str = 'http://' . $str;
        }

        $urlParts = parse_url($str);

        // remove www
        $domain = preg_replace('/^www\./', '', $urlParts['host']);

        // output: google.co.uk
        return $domain;
    }
}

if (! function_exists('_is_curl_installed'))
{
    /**
     * check if curl is installed on the server
     *
     * @return bool
     */
    function _is_curl_installed() {
        if  (in_array  ('curl', get_loaded_extensions())) {
            return true;
        }
        else {
            return false;
        }
    }
}

if (! function_exists('redis'))
{
    /**
     * Return an instance of our custom redis repository
     */
    function redis()
    {
        return resolve(RedisRepository::class);
    }
}

if (! function_exists('dd_blade_variables'))
{
    /**
     * dd all variables passed to blade
     * Does not work here: only in blade files
     */
    function dd_blade_variables()
    {
        dd(get_defined_vars()['__data']);
    }
}

if (! function_exists('is_collection'))
{
    /**
     * Check if a variable is a collection, similar to is_array()
     *
     * @param mixed $variable
     * @return bool
     */
    function is_collection($variable)
    {
        return $variable instanceof Illuminate\Support\Collection;
    }
}

if (! function_exists('is_serialized'))
{
    /**
     * check if is serialized or not.
     * Borrowed from WordPress
     */
    function is_serialized($data)
    {
        // if it isn't a string, it isn't serialized
        if (! is_string($data))
            return false;
        $data = trim($data);
        if ('N;' == $data)
            return true;
        if (! preg_match('/^([adObis]):/', $data, $badions))
            return false;
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;
                break;
        }

        return false;
    }
}

if (! function_exists('sanitizeBladeUrl'))
{
    /**
     * sanitize blade url
     *
     * @param string $url
     * @param string $realBaseUrl
     *
     * @return string
     */
    function sanitizeBladeUrl(string $url, string $realBaseUrl) : string
    {
        $str = "";
        
        // remove 'localhost' from the $realBaseUrl
        if(str_contains($url, 'localhost')) {
            $urlParts = collect(explode('localhost', $url))->slice(1);
            $str = $realBaseUrl . implode('/', $urlParts->toArray());
        }
    
        if(str_contains($url, request()->root())) {
            $urlParts = collect(explode(request()->root(), $url))->slice(1);
            $str = $realBaseUrl . implode('/', $urlParts->toArray());
        }
    
        return $str;
    }
}

if (! function_exists("relativeUrl"))
{
    /**
    * Formats an absolute url to a relative url; strip the root domain from the url
    */
    function relativeUrl($url)
    {
        return str_replace(request()->root(), '', $url);
    }
}

if (! function_exists('is_countable')) {
    
    /**
     * a polyfill for the php 7.3 function
     */
    function is_countable($c) {
        return is_array($c) || $c instanceof \Countable;
    }
}

if (! function_exists('filenameSanitizer')) 
{    
    /** 
    * filename sanitizer
    *
    * @var mixed Request
    */
    function filenameSanitizer($str) {
        $nicename = str_replace(' ', '-', strtolower($str));
        // Remove anything which isn't a word, whitespace, number,
        // or any of the following characters -_~,;[]().
        // if you don't need to handle multi-byte characters
        // you can use preg_replace rather than mb_ereg_replace
        $nicename = preg_replace('([^\w\s\d\-_~,;\[\]\(\).])', '', $nicename);
        // remove any runs of periods
        $nicename = preg_replace('([\.]{2,})', '', $nicename);
        // remove any non-alpha-numeric characters
        $nicename = preg_replace("/[^a-zA-Z0-9]+/", "", $nicename);
        // ensure the length is more than ten characters
        if(strlen($nicename) < 10)
            $nicename .= "-" . Str::random(10);

        return $nicename;
    }
}

if (! function_exists('command_exists'))
{
    /**
     * Check if an artisan command exists
     *
     * @param $name
     *
     * @return bool
     */
    function command_exists($name)
    {
        return array_has(Artisan::all(), $name);
    }
}

if (! function_exists('setting'))
{
    /**
     * Retrieve a setting configuration value.
     * no need in prefixing the module's name since they are unique
     *
     * @param $setting
     * @param string|null $default
     *
     * @return mixed
     */
    function setting($setting, $default = null)
    {
        return redis()->get('configurations')->where('name', $setting)->first()->value ?? $default;
    }
}

if (! function_exists('t_asset'))
{
    /**
     * Get the assets for a template in the system
     */
    function t_asset($path = null)
    {
        $vars = explode('::', $path);
    
        // construct to templates/theme/path
        return asset('templates/' . $vars[0] . '/' . $vars[1]);
    }
}

if (! function_exists('instanceOfResource'))
{
    /**
     * Check if data is an instance of resource, jsonResource, or resourceCollection
     */
    function instanceOfResource($data)
    {
        return $data instanceof ResourceCollection
            || $data instanceof JsonResource;
    }
}

if (! function_exists('authorize'))
{
    /**
     * Check user authorization and throw error if false
     *
     * @param string|array $permissionOrRole
     * @param bool $issaRole
     */
    function authorize($permissionOrRole, bool $issaRole = false)
    {
        $can = false;

        if(doe()) {
            $can = $issaRole ? doe()->hasRole($permissionOrRole) : doe()->can($permissionOrRole);
        }

        if(! $can) {
            $handler = Config::get("laratrust.middleware.handlers.abort");
            $defaultMessage = 'User does not have any of the necessary access rights.';

            $message = "[CODE: " . is_array($permissionOrRole) ? json_encode($permissionOrRole) : $permissionOrRole . "]";
            $message .= $handler['message'] ?? $defaultMessage;

            return App::abort($handler['code'], $message);
        }
    }
}

if (! function_exists('deny'))
{
    /**
     * custom abort code for non-authorized persons
     *
     * @param string|null $message
     */
    function deny(string $message = null)
    {
        $handler = Config::get("laratrust.middleware.handlers.abort");

        $defaultMessage = 'User does not have any of the necessary access rights.';
        return App::abort($handler['code'], $message ?? $handler['message'] ?? $defaultMessage);
    }
}

if (! function_exists('getLogoDomainWise'))
{
    /**
     * Get the logo domain-wise. If a sub-domain, get the logo on the first part
     * 
     * @return string $logoPath
     */
    function getLogoDomainWise()
    {
        $domain = explode(".", sanitizeDomainUrl());
        array_pop($domain);
        $domain = implode('-', $domain);
        return "/logos/" . $domain . ".png";
    }
}

if (! function_exists('__ta')) {
    /**
     * The acelords root template directory w.r.t. the theme root template directory.
     *
     * @param $template
     *
     * @param $useParent
     *
     * @return string
     */
    function __ta($template, $useParent = false)
    {
        return "/templates/$template/";
    }
}

if (! function_exists('__m')) 
{
    /**
     * Returns the mix-manifest.json file
     *
     * @param $template
     * @param $useParent
     *
     * @return bool|string
     */
    function __m($template, $useParent)
    {
        $template_name = "mix-manifest.json";

        // Force the Parent Manifest
        if ($useParent and file_exists(__ta($template) . $template_name)) {
            return __ta($template) . $template_name;
        }

        // Check the Child Manifest
        if (file_exists(__ta($template) . $template_name)) {
            return __ta($template) . $template_name;
        }

        // Check AceLords Manifest.
        if (file_exists(__ta($template) . '/acelords/public/' . $template_name)) {
            return __ta($template) . '/acelords/public/' . $template_name;
        }

        // Return to the Core Manifest.
        if (file_exists(__ta($template) . '/' . $template_name)) {
            return __ta($template) . '/' . $template_name;
        }

        return false;
    }
}

if (! function_exists('__mix')) 
{
    /**
     * @param $path
     *
     * @param bool $useParent
     *
     * @return string
     */
    function __mix($path, $useParent = false)
    {
        $arr = explode('::', $path);
        $template = $arr[0];
        $assetPath = $arr[1];

        $pathWithOutSlash = ltrim($assetPath, '/');
        $pathWithSlash    = '/' . ltrim($assetPath, '/');
        $manifestFile     = __m($template, $useParent);

        // No manifest file was found so return whatever was passed to mix().
        if ( ! $manifestFile) {
            return __ta($template, $useParent) . $pathWithOutSlash;
        }

        $manifestArray = json_decode(file_get_contents($manifestFile), true);

        if (array_key_exists($pathWithSlash, $manifestArray)) {
            return __ta($template, $useParent) . ltrim($manifestArray[$pathWithSlash], '/');
        }

        // No file was found in the manifest, return whatever was passed to mix().
        return __ta($template, $useParent) . $pathWithOutSlash;
    }
}

if (! function_exists('get_hours_and_days_count'))
{
    /**
     * get number of hours and days given total hours
     * 
     * @param int $hours
     */
    function get_hours_and_days_count($hours)
    {
        $date1 = date_create(date('Y-m-d'));
        $date2 = date_create(date('Y-m-d', strtotime('+' . $hours . ' hours')));
        if($hours <= 48) {
            return $hours . ' hours';
        }

        $no_of_days = date_diff($date1, $date2);
        return $no_of_days->format('%a days');
    }
}

if (! function_exists('get_gross_hours'))
{
    /**
     * get the gross hours for a certain date
     *
     * @param $date
     *
     * @return float|int
     */
    function get_gross_hours($date)
    {
        $diff = $date->diff(now());
        $hours = $diff->h;
        $hours = $hours + ($diff->days*24);

        if($diff->i > 30)
            $hours++;

        return $hours;
    }
}

if (! function_exists('isDemo'))
{
    /**
     * Check if app is in demo
     */
    function isDemo() : bool
    {
        return app()->environment() == "demo";
    }
}

if (! function_exists('gravatar'))
{
    /**
     * get profile picture from gravatar
     */
    function gravatar(string $name) : string
    {
        $gravatarId = md5(strtolower(trim($name)));

        return 'https://gravatar.com/avatar/' . $gravatarId . '?s=240';
    }
}

if (! function_exists('sizeForHumans'))
{
    /**
     * get file sizes that are human readable
     * @param $size // pass in kilobytes (like Laravel storage does)
     */
    function sizeForHumans($size) : string
    {
        // $bytes = $size;
        // if ($bytes >= 1000000000) {
        //     $bytes = number_format($bytes / 1000000000, 1) . 'GB';
        // } elseif ($bytes >= 1000000) {
        //     $bytes = number_format($bytes / 1000000, 1) . 'MB';
        // } elseif ($bytes >= 1000) {
        //     $bytes = number_format($bytes / 1000, 0) . 'KB';
        // } elseif ($bytes > 1) {
        //     $bytes = $bytes . ' bytes';
        // } elseif ($bytes == 1) {
        //     $bytes = $bytes . ' byte';
        // } else {
        //     $bytes = '0 bytes';
        // }

        $kiloBytes = $size;

        if($kiloBytes < 1000)
            return $kiloBytes . ' KB';
        else if($kiloBytes < (1000 * 1000))
            return number_format(($kiloBytes / 1000), 2) . ' MB';
            
        return number_format(($kiloBytes / (1000 * 1000)), 2) . ' GB';
    }
}

if (! function_exists('localizeDate'))
{
    /**
     * Convert the given date to the $timezone or logged in users timezone.
     *
     * @param mixed $date A Carbon date or a parsable date format.
     * @param string $timezone A PHP timezone. If null it will use the logged in users timezone.
     * @return string The localized date.
     */
    function localizeDate($date, $timezone = null)
    {
        if (!($date instanceof Carbon)) {
            $date = is_numeric($date) ? Carbon::createFromTimestamp($date) : Carbon::parse($date);
        }

        return $date->setTimezone($timezone ?? (doe() ? doe()->timezone : 'UTC'));
    }
}

if (! function_exists('localizeDateFormat'))
{
    /**
     * Convert the given date to the $timezone or logged in users timezone.
     * Then format it to the given format.
     *
     * @param mixed $date A Carbon date, array of parameters or a parsable date format.
     * @param string $format A PHP date time format.
     * @param string $timezone A PHP timezone. If null it will use the logged in users timezone.
     * @return string The formatted date.
     */
    function localizeDateFormat($date, $format = 'jS M Y, g:ia', $timezone = null)
    {
        // Support array input as primary arg
        if (is_array($date) && !empty($date)) {

            // If format exists
            if (count($date) >= 2) {
                $format = $date[1];
            }

            // If timezone exists
            if (count($date) >= 3) {
                $timezone = $date[2];
            }

            $date = $date[0];
        }

        return localizeDate($date, $timezone)->format($format);
    }
}
