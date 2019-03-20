<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaylistResource;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PlaylistResource::collection(Playlist::with('tracks')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, Playlist::$rules);
        $newPlayList = auth()->user()->createPlaylist(new Playlist($request->all()));

        return Response::json(["message" => "success", "data" => $newPlayList], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Playlist $playlist
     * @return PlaylistResource
     */
    public function show(Playlist $playlist)
    {
        return new PlaylistResource($playlist);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Playlist $playlist
     * @param Track $track
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Playlist $playlist, Track $track)
    {
        $playListWithTrack = auth()->user()->addTrackToPlaylist($playlist, $track);
        return Response::json(["success" => is_null($playListWithTrack) ? true : false, "message" => is_null($playListWithTrack) ? "Track added to playlist" : "Track already exists in the playlist"],is_null($playListWithTrack)?201:400);
    }
}
