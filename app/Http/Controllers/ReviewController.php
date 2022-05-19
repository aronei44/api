<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReviewResource::collection(Review::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'star'=>'required|min:1|max:5|numeric',
            'comment'=>'required|min:1|max:255',
        ]);
        $user = $request->user();
        $review = Review::where('user_id', $user->id)->first();
        if ($review) {
            return new ReviewResource($review);
        }
        $review = Review::create([
            'star' => $request->star,
            'comment' => $request->comment,
            'user_id' => $user->id,
        ]);
        return new ReviewResource($review);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $review = Review::where('user_id', $user->id)->first();
        if (!$review) {
            return response()->json([
                'message' => 'You have not reviewed yet',
            ], 204);
        }
        return new ReviewResource($review);
    }

}
