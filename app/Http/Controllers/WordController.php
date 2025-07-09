<?php

namespace App\Http\Controllers;

use App\Models\Model3D;
use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allWords = Word::find(2);
        // dd(url($chars->path));



        $response = [
            'message' => 'allwords retreived',
            'data' =>  $allWords ,
            'status' => 200
        ];

        return response()->json($response , 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Word $word)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Word $word)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Word $word)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word $word)
    {
        //
    }
}
