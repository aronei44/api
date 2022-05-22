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
            'provinsi' => $this->provinsi,
            'provinsi_id' => $this->provinsi_id,
            'kabupaten' => $this->kabupaten,
            'kabupaten_id' => $this->kabupaten_id,
            'kecamatan' => $this->kecamatan,
            'kecamatan_id' => $this->kecamatan_id,
            'desa' => $this->desa,
            'desa_id' => $this->desa_id,
            'alamat' => $this->alamat,
            'deskripsi' => $this->deskripsi,
            'bidang_usaha' => $this->bidang_usaha,
            'no_hp' => $this->no_hp,
            'user' => new UserResource(User::find($this->user_id)),
        ];
    }
}
