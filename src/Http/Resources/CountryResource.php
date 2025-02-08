<?php

namespace AceLords\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => (int) $this->id,
            'name'                  => $this->name,
            'slug'                  => $this->slug,
            'iso_code'              => $this->iso_code,
            'country_code'          => $this->country_code,
        ];
    }
}
