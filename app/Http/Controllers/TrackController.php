<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Album $album)
    {
        return Response::json(Track::OfAlbum($album->id)->paginate(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Album $album)
    {
        $this->validate(Track::$rules);
        $track = new Track($request->all());
        if ($request->hasFile('file_url')) {
            $image = $request->file('file_url');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $name); // => storage/app/public/file.img
            $file_url = URL::asset('storage/' . $name); // => http://example.com/stoage/file.img
            $track->file_url = $file_url;
        }
        $newAddedTrack = auth()->user()->addTrack($track, $album);
        if (!$newAddedTrack instanceof Track) {
            $response = response()->json(
                [
                    "error" => [
                        "message" => "The track could not be created."
                    ]],
                400
            );
            return $response;
        }

        return response()->json(
            ["message" => "The track  has been created successfully",
                "data" => $newAddedTrack],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Track $track
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track)
    {
        return TrackResource($track);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Track $track
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album, Track $track)
    {
        $isTrackUpdated = auth()->user()->updateTrack($request->all(), $album, $track);

        return Response::json(["message" => $isTrackUpdated ? "Track updated successfully" : "Failed to delete track", "data" => $isTrackUpdated ? $track : ""], $isTrackUpdated ? 200 : 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Track $track
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album, Track $track)
    {
        $isAlbumDeleted = auth()->user()->deleteTrack($album, $track);
        return Response::json(["message" => $isAlbumDeleted ? "Track deleted successfully" : "Failed to delete Track"], $isAlbumDeleted ? 200 : 400);

    }
}
