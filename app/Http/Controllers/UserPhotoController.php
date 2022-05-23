<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\DeletedResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ServerErrorResource;
use App\Http\Resources\UnauthorizedResource;

class UserPhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic',
        ]);
        try {
            $photo = $request->photo->store('','google');
            $url = Storage::disk('google')->url($image);
            $photo = Photo::create([
                'name' => $request->name,
                'url' => $url,
                'user_id'=> $request->user()->id,
            ]);
            return (new PhotoResource($photo))->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            //throw $th;
            return (new ServerErrorResource($th))->response()->setStatusCode(500);
        }
    }
    public function bulkStore(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic',
        ]);
        try {
            $photos = [];
            foreach($request->photos as $photo) {
                try {
                    $url = $photo->store('','google');
                    $url = Storage::disk('google')->url($image);
                    $photo = Photo::create([
                        'name' => $request->name,
                        'url' => $url,
                        'user_id'=> $request->user()->id,
                    ]);
                    $photos[] = $photo;
                } catch (\Throwable $th) {

                }
            }
            return (PhotoResource::collection($photos))->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            //throw $th;
            return (new ServerErrorResource($th))->response()->setStatusCode(500);
        }
    }

    public function update(Request $request, Photo $photo)
    {
        if($photo->user_id != $request->user()->id) {
            return (new UnauthorizedResource(1))->response()->setStatusCode(403);
        }
        try {
            $photos = Photo::where('user_id', $request->user()->id)->get();
            foreach ($photos as $photoData) {
                $photoData->is_main = false;
                $photoData->save();
            }
            $photo->is_main = true;
            $photo->save();
            return (new PhotoResource($photo))->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new ServerErrorResource($th))->response()->setStatusCode(500);
        }
    }

    public function destroy(Request $request, Photo $photo)
    {
        if($photo->user_id != $request->user()->id) {
            return (new UnauthorizedResource(1))->response()->setStatusCode(403);
        }
        try {
            if($photo->is_main) {
                $photos = Photo::where('user_id', $request->user()->id)
                                ->where('is_main', false)
                                ->first();
                $photos->is_main = true;
                $photos->save();
            }
            $photo->delete();
            return (new DeletedResource())->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return (new ServerErrorResource($th))->response()->setStatusCode(500);
        }
    }
}
