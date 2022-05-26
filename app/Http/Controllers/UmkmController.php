<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\PhotoUmkm;
use Illuminate\Http\Request;
use App\Http\Requests\UmkmRequest;
use App\Http\Resources\UmkmResource;
use App\Http\Resources\DeletedResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\NotFoundResource;
use App\Http\Resources\DuplicateResource;
use App\Http\Resources\ServerErrorResource;
use App\Http\Resources\UnauthorizedResource;

class UmkmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UmkmResource::collection(Umkm::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UmkmRequest $request)
    {
        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;
        $umkm = Umkm::where('user_id', $user_id)->first();
        if ($umkm) {
            return (new DuplicateResource(1))->response()->setStatusCode(422);
        }
        try {
            $umkm = Umkm::create([
                'nama' => $request->nama,
                'provinsi' => $request->provinsi,
                'provinsi_id' => $request->provinsi_id,
                'kabupaten' => $request->kabupaten,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan' => $request->kecamatan,
                'kecamatan_id' => $request->kecamatan_id,
                'desa' => $request->desa,
                'desa_id' => $request->desa_id,
                'alamat' => $request->alamat,
                'deskripsi' => $request->deskripsi,
                'bidang_usaha' => $request->bidang_usaha,
                'no_hp' => $request->no_hp,
                'user_id' => $user_id,
                'owner_name' => $request->owner_name,
                'gender' => $request->gender,
            ]);
            try {
                $image = $request->photo->store('','google');
                $url = Storage::disk('google')->url($image);
                $photo = PhotoUmkm::create([
                    'photo' => $image,
                    'url' => $url,
                    'umkm_id'=> $umkm->id,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
            return (new UmkmResource($umkm))->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            return (new ServerErrorResource($th))->response()->setStatusCode(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Umkm  $umkm
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
        if($id) {
            $umkm = Umkm::find($id);
            if (!$umkm) {
                return (new NotFoundResource(1))->response()->setStatusCode(404);
            }
            return (new UmkmResource($umkm))->response()->setStatusCode(200);
        }
        $umkm = Umkm::with('user')->where('user_id', $request->user()->id)->first();
        if(!$umkm) {
            return (new NotFoundResource(1))->response()->setStatusCode(404);
        }
        return (new UmkmResource($umkm))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Umkm  $umkm
     * @return \Illuminate\Http\Response
     */
    public function update(UmkmRequest $request, Umkm $umkm)
    {
        if($umkm->user_id != $request->user()->id) {
            return (new UnauthorizedResource(1))->response()->setStatusCode(403);
        }
        try {
            $umkm->update([
                'nama' => $request->nama,
                'provinsi' => $request->provinsi,
                'provinsi_id' => $request->provinsi_id,
                'kabupaten' => $request->kabupaten,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan' => $request->kecamatan,
                'kecamatan_id' => $request->kecamatan_id,
                'desa' => $request->desa,
                'desa_id' => $request->desa_id,
                'alamat' => $request->alamat,
                'deskripsi' => $request->deskripsi,
                'bidang_usaha' => $request->bidang_usaha,
                'no_hp' => $request->no_hp,
                'owner_name' => $request->owner_name,
                'gender'=> $request->gender,
            ]);
            return (new UmkmResource($umkm))->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new ServerErrorResource($th))->response()->setStatusCode(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Umkm  $umkm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Umkm $umkm)
    {
        try {
            if($umkm->user_id != $request->user()->id) {
                return (new UnauthorizedResource(1))->response()->setStatusCode(403);
            }
            foreach($umkm->photos as $photo) {
                Storage::disk('google')->delete($photo->photo);
                $photo->delete();
            }
            $umkm->delete();
            return (new DeletedResource(1))->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new ServerErrorResource($th))->response()->setStatusCode(500);
        }
    }
}
