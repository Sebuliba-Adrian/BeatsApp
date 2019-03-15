<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Album $album, Track $track)
    {
        $comments = auth()->user()->listComments($album, $track);
        return Response::json(["message" => $comments ? "success" : "error", "data" => $comments ? $comments : ""], $comments ? 200 : 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Album $album
     * @param Track $track
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request, Album $album, Track $track)
    {
        $this->validate($request, Comment::$rules);
        $comment = new Comment($request->all());
        $comment->user_id = auth()->user()->id;
        $newComment = auth()->user()->addComment($album, $track, $comment);

        return Response::json(["message" => $newComment ? "Comment created successfully" : "Failed to create comment", "data" => $newComment ? $newComment : ""], $newComment ? 201 : 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album, Track $track, Comment $comment)
    {
        $comment = auth()->user()->showComments($album, $track, $comment);
        return Response::json(["message" => $comment ? "success" : "error", "data" => $comment ? $comment : ""], $comment ? 200 : 400);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Comment $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album, Track $track, Comment $comment)
    {
        $isCommentUpdated = auth()->user()->updateComment($request->all(), $album, $track, $comment);
        return Response::json(["message" => $isCommentUpdated ? "The comment has been updated" : "Failed to update comment"], $isCommentUpdated ? 200 : 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album $album
     * @param Track $track
     * @param  \App\Models\Comment $comment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Album $album, Track $track, Comment $comment)
    {
        $isCommentDeleted = auth()->user()->deleteComment($album, $track, $comment);
        return Response::json(["message" => $isCommentDeleted ? "The comment has been deleted" : "Failed to delete comment"], $isCommentDeleted ? 200 : 400);
    }
}
