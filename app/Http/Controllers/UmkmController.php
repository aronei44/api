<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;
use App\Http\Requests\UmkmRequest;

class UmkmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $umkms = Umkm::with('user')->get();
        return response()->json([
            'status' => 'success',
            'data' => $umkms
        ], 200);
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
            return response()->json([
                'status' => 'error',
                'message' => 'User sudah memiliki umkm'
            ], 400);
        }
        try {
            $umkm = Umkm::create([
                'nama' => $request->nama,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'kampung' => $request->kampung,
                'desa' => $request->desa,
                'alamat' => $request->alamat,
                'deskripsi' => $request->deskripsi,
                'bidang_usaha' => $request->bidang_usaha,
                'no_hp' => $request->no_hp,
                'user_id' => $user_id
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $umkm
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Umkm  $umkm
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $umkm = Umkm::with('user')->where('user_id', $request->user()->id)->first();
        if(!$umkm) {
            return response()->json([
                'status' => 'error',
                'message' => 'Umkm not found'
            ], 204);
        }
        return response()->json([
            'status' => 'success',
            'data' => $umkm
        ], 200);
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
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to update this umkm'
            ], 400);
        }
        try {
            $umkm->update([
                'nama' => $request->nama,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'kampung' => $request->kampung,
                'desa' => $request->desa,
                'alamat' => $request->alamat,
                'deskripsi' => $request->deskripsi,
                'bidang_usaha' => $request->bidang_usaha,
                'no_hp' => $request->no_hp,
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $umkm
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
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
        if($umkm->user_id != $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this umkm'
            ], 400);
        }
        try {
            $umkm->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Umkm deleted'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
