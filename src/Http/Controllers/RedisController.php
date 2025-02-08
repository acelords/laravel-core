<?php

namespace  AceLords\Core\Http\Controllers;

use Illuminate\Routing\Controller;

class RedisController extends Controller
{
    protected $repo;

    /**
     * Initialise class
     */
    public function __construct()
    {
        $this->repo = redis();
    }

    /**
     * Display a listing of the resource.
     */
    public function fetch()
    {
        $items = request()->except(['page']);

        $collection = array();
        $protectedItems = collect(config('acelords_redis.extras'))->filter(function($item) {
            return is_array($item);
        });

        foreach($items as $item)
        {
            $itemName = snake_case(camel_case($item));
            // perform authorization of item requested
            if(in_array($itemName, $protectedItems->pluck('table')->toArray())) {
                // check role/permission
                $itemToBeChecked = $protectedItems->firstWhere('table', $itemName);
                $user = doe();
                if(! $user || ($user && ! $user->hasRole($itemToBeChecked['roles']))) {
                    deny("Unauthorised Access!");
                }
            }

            $collection[camel_case($item)] = $this->repo->get($itemName);
        }

        return response()->json($collection);
    }

}
