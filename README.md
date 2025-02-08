# AceLords Core Composer Package
An AceLords core composer package

## Facades
- SeoManager
```php

// use AceLords\Core\Library\Facades\SeoManager;

SeoManager::page(strtolower($page))->data();
```

## Commands
- acelords:clear-redis
- acelords:fix-roles
- acelords:generate-product-key
- acelords:sudofy
- acelords:test-queue
- acelords:truncate-table
- acelords:update-redis-key

## Helper Functions
- core_paginate(string $size) : int
- dde($var) : void
- doe() : Auth::user()
- eclair(string|Carbon $date, bool? $time = true, bool? $toW3cString = false) : Carbon
- sanitizeDomainUrl(string? $url)
- _is_curl_installed() : bool
- redis() : RedisRepository
- dd_blade_variables() : void
- is_collection($var) : bool
- is_serialized($var) : bool
- sanitizeBladeUrl(string $url, string $realBaseUrl) : string
- relativeUrl(string $url) : string
- is_countable($var) : bool
- filenameSanitizer(string $str) : string
- command_exists(string $command) : bool
- setting(string $str, bool? $default) : string|null
- t_asset(string $path) : string
- instanceOfResource($data) : bool
- authorize(string $permissionOrRole, bool? $issaRole = false) : void|Exception
- deny(string? $message) : Exception
- getLogoDomainWise() : string
- __ta(string $template, bool? $useParent) : string
- __m(string $template, bool? $useParent) : string
- __mix(string $path, bool? $useParent) : string
- get_hours_and_days_count(int $hours) : string
- get_gross_hours(Date|Carbon $date) : float|int
- isDemo() : bool
- gravatar(string $name) : string
- sizeForHumans(int|float $size) : string

## Credits
- [AceLords](https://www.acelords.com)
- [Lexx YungCarter](mailto:lexxyungcarter@gmail.com)
- [Github](https://github.com/acelords)
