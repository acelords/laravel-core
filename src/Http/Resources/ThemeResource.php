<?php

namespace AceLords\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
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
            'domain_id'             => $this->domain_id,
            'name'                  => $this->name,

            // dates
            'created_at'            => eclair($this->created_at),
            'created_at_w3c'        => eclair($this->created_at, true, true),

            // relationships
            'domain_name'           => $this->domain->name ?? null,

        ];
    }
}
