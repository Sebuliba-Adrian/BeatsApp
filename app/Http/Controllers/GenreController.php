<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Validation\ValidationException;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Genre::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, Genre::$rules);
        if (!auth()->user()->is_artist) {
            return response()->json(["message" => "You are not allowed to do this"]);
        }

        $genre = new Genre($request->all());
        $genre->save();

        return response()->json(
            ["message" => "The genre has been created successfully",
                "data" => $genre],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre $genre
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Genre $genre)
    {
        $isGenreDeleted = $genre->delete();
        return response()->json(
            ["success" => $isGenreDeleted, "message" => $isGenreDeleted ? "The genre has been deleted successfully" : "Failed deleting genre"],
            $isGenreDeleted ? 201 : 400
        );

    }
}
