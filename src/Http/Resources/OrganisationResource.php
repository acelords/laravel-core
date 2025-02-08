<?php

namespace AceLords\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
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
            'country_id'            => $this->country_id,
            'name'                  => $this->name,
            'address'               => $this->address,
            'town'                  => $this->town,
            'telephone'             => $this->telephone,
            'mobile'                => $this->mobile,
            'email'                 => $this->email,
            'no_reply_email'        => $this->no_reply_email,
            'support_email'         => $this->support_email,
            'office'                => $this->office,
            'street'                => $this->street,
            'building'              => $this->building,
            'fax'                   => $this->fax,
            'pin'                   => $this->pin,
            'logo'                  => $this->logo,
            'slogan'                => $this->slogan,
            'bio'                   => $this->bio,
            'theme_color'           => $this->theme_color,
            'social_profiles'       => $this->social_profiles,

            // dates
            'created_at'            => eclair($this->created_at),
            'created_at_w3c'        => eclair($this->created_at, true, true),
            'updated_at'            => eclair($this->updated_at),
            'updated_at_w3c'        => eclair($this->updated_at, true, true),

            // relationships
            'domain_name'           => $this->domain->name ?? null,

        ];
    }
}
