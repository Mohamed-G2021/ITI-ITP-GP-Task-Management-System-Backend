<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    function __construct()
    {
        return $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $comments = Comment::all();
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "content" => "required"
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }

        $comment = Comment::create($request->all());
        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
        $validator = Validator::make($request->all(), [
            "content" => "required"
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }


        $comment->update($request->all());

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
        $comment->delete();
        return response("Deleted", 204);
    }
}
