<?php

namespace App\Http\Controllers;

use App\Models\Model3D;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Model3DController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $model = Model3D::all();
        $response = [
            'message' => 'data retrieved success',
            'data' => $model,
            'status' => 200
        ];

        return response()->json($response ,200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'path' => 'required|file|mimes:glb,bin,gltf'
        ]);

        if ($request->hasFile('path')) {
            $file = $request->file('path');

            $file_name = time() . "-" .  $file->getClientOriginalName();
            $file_path = $file->storeAs('public/models', $file_name);

            $model = Model3D::create([
                'name' => $request->name,
                'path' => Storage::url($file_path),
            ]);

            $response = [
                'message' => 'file uploaded success',
                'status' => 200,
                'success' => true,
                'data' => [
                    'url' => asset($model->path)
                ]
            ];


            return response()->json($response, 200);
        } else {
            $response = [
                'message' => 'No file was uploaded',
                'status' => 400,
                'success' => false
            ];
            return response()->json($response, 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Model3D $model3D , string $id)
    {

        $model = Model3D::find($id);
        // dd($model);
        if(!empty($model)) {
            $response = [
                'message' => 'data retrieved success' ,
                'data' => $model ,
                'status' => 200,
                'success' => true
            ];
        } else {
            $response = [
                'message' => 'data not found' ,
                'status' => 401 ,
                'success' => false
            ];
        }

        return response()->json($response , 200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Model3D $model3D)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Model3D $model3D)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Model3D $model3D)
    {
        //
    }
}
