<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AlbumResource::collection(Album::with(['genre', 'user'])->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, Album::$rules);

        if (!auth()->user()->is_artist) {
            return response()->json(["message" => "You are not allowed to do this"]);
        }

        $album = auth()->user()->createAlbum(new Album($request->all()));

        return response()->json(
            ["message" => "The album has been created successfully",
                "data" => $album],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album $album
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        return Response::json($album, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @param  \App\Models\Album $album
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Album $album)
    {
        $isAlbumUpdated = auth()->user()->updateAlbum($request->all(), $album);
        return Response::json(
            ["message" => $isAlbumUpdated ? "The album has been updated successfully" : "couldn't update album", "data" => $isAlbumUpdated ? $album : ""],
            $isAlbumUpdated ? 200 : 400
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album $album
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Album $album)
    {
        $isAlbumDeleted = auth()->user()->deleteAlbum($album);

        return Response::json(["message" => $isAlbumDeleted ? "The album has been deleted" : "Failed to delete album"], $isAlbumDeleted ? 200 : 400);
    }
}
