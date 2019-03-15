<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaylistResource;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Support\Facades\Response;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PlaylistResource::collection(Playlist::with('tracks')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, Playlist::$rules);
        $newPlayList = auth()->user()->createPlaylist(new Playlist($request->all()));

        return Response::json(["message" => "success", "data" => $newPlayList]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Playlist $playlist
     * @return \Illuminate\Http\Response
     */
    public function show(Playlist $playlist)
    {
        return new PlaylistResource($playlist);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Playlist $playlist
     * @return \Illuminate\Http\Response
     */
    public function add(Playlist $playlist, Track $track)
    {
        $playListWithTrack = auth()->user()->addTrackToPlaylist($playlist, $track);
        return Response::json(["message" => is_null($playListWithTrack) ? "success" : "error", "data" => is_null($playListWithTrack) ? "Track added to playlist" : "Track already exists in the playlist"]);

    }

}
