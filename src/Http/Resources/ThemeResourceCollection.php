<?php

namespace AceLords\Core\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use AceLords\Core\Library\Datatable;

class ThemeResourceCollection extends ResourceCollection
{
    /** 
     * Define the headers and consequently values for the datatable
     */
    protected $headers = [
        ['text' => '#', 'value' => 'id'],
        'name', 'domain',
        'actions',
    ];

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'      => $this->collection,

            'headers'   => (new Datatable)->headers($this->headers)
        ];
    }
}
