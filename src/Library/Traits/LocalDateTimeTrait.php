<?php
/**
 * Created by PhpStorm.
 * User: lexxyungcarter
 * Date: 03/12/2021
 * Time: 15:45
 */

namespace AceLords\Core\Library\Traits;

use Illuminate\Support\Carbon;

trait LocalDateTimeTrait
{
    /**
     * set locatimezone
     */
    private function localTimezone($value = null)
    {
        $loggedInUser = doe();

        if($loggedInUser)
            return $value ? Carbon::parse($value)->setTimezone($loggedInUser->timezone ?? 'UTC')->toDateTimeString() : null;
        
        return $value ? Carbon::parse($value)->setTimezone('UTC')->toDateTimeString() : null;
    }

    /**
     * created_at accessor in local timezone
     */
    public function getCreatedAtAttribute($value)
    {
        return $this->localTimezone($value);
    }

    /**
     * updated_at accessor in local timezone
     */
    public function getUpdatedAtAttribute($value)
    {
        return $this->localTimezone($value);
    }
    
    /**
     * deleted_at accessor in local timezone
     */
    public function getDeletedAtAttribute($value)
    {
        return $this->localTimezone($value);
    }

    /**
     * blocked_at accessor in local timezone
     */
    public function getBlockedAtAttribute($value)
    {
        return $this->localTimezone($value);
    }
}