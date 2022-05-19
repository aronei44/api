<?php

namespace App\Http\Resources;

use App\Models\Photo;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email ? $this->email : null,
            'photo' => new PhotoResource(Photo::where('user_id', $this->id)->where('is_main',true)->first()),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
