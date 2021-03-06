<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        //return parent::toArray($request);
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'avatar'            => $this->getAvatar(),
            'username'          => $this->username,
            'email'             => $this->email,
            'company_id'        => $this->company_id,
            'activated'         => $this->activated,
            'created_at'        => Carbon::instance(new \DateTime($this->created_at))->format('Y-m-d'),
            'updated_at'        => Carbon::instance(new \DateTime($this->updated_at))->format('Y-m-d'),

            'company'           => new HeaderCompanyResource($this->company),
        ];
    }
}
