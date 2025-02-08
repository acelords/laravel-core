<?php
/**
 * Created by PhpStorm.
 * User: lexxyungcarter
 * Date: 05/10/2019
 * Time: 15:17
 */

namespace AceLords\Core\Library\Facades;

use AceLords\Core\Library\Services\SeoManagerService;
use Illuminate\Support\Facades\Facade;

class SeoManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SeoManagerService::class;
    }
}
