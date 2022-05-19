<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UmkmResource extends JsonResource
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
            'nama' => $this->nama,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'kampung' => $this->kampung,
            'desa' => $this->desa,
            'alamat' => $this->alamat,
            'deskripsi' => $this->deskripsi,
            'bidang_usaha' => $this->bidang_usaha,
            'no_hp' => $this->no_hp,
            'user' => new UserResource(User::find($this->user_id)),
        ];
    }
}
