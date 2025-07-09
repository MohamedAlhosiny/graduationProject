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
        $models = Model3D::all()->map(function ($model) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'url' => url($model->path),
            ];
        });

        return response()->json([
            'message' => 'Data retrieved successfully',
            'status' => 200,
            'data' => $models
        ], 200);
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
                    'url' => url($model->path)
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
    public function show(Model3D $model3D, string $id)
    {

        $model = Model3D::find($id);
        // dd($model);
        if (!empty($model)) {
            $response = [
                'message' => 'data retrieved success',
                'data' => [
                    $model->id,
                    $model->name,
                    url('storage/' . $model->path),
                    $model->created_at,
                    $model->updated_at
                ],
                'status' => 200,
                'success' => true
            ];
        } else {
            $response = [
                'message' => 'data not found',
                'status' => 401,
                'success' => false
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Model3D $model3D)
    {
        //
    }


    public function update(Request $request, string $id)
    {


        $model = Model3D::find($id);

        if (!empty($model)) {
            $request->validate([
                'name' => 'nullable|string',
                'path' => 'nullable|file|mimes:glb,bin,gltf'
            ]);
            $model->name = $request->name;

            $model->update();

            $newFile = $request->file('path');
            if ($newFile && $newFile->isValid()) {
                if ($model->path) {
                    $oldFilePath = str_replace('storage/', '', $model->path);
                    Storage::disk('public')->delete($oldFilePath);
                }

                $fileName = time() . '-' . $newFile->getClientOriginalName();
                $filePath = $newFile->storeAs('models', $fileName, 'public');

                $model->path = $filePath;
            }
            $model->save();
            $response = [
                'message' => 'model updated success',
                'data' => [
                    $model->id,
                    $model->name,
                    url('storage/' . $model->path)
                ],

                'status' => 200
            ];

            return response()->json($response, 200);
        } else {
            $response = [
                'message' => 'model not found to update',
                'status' => 404
            ];

            return response()->json($response, 400);
        }
    }


    public function destroy(string $id)
    {
        $deleteModel = Model3D::find($id);

        if (!empty($deleteModel)) {
            if (!empty($deleteModel->path)) {
                $filePath = str_replace('storage/', '', $deleteModel->path);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            $deleteModel->delete();

            $response = [
                'message' => 'Model deleted successfully',
                'status' => 204
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => 'Model not found',
                'status' => 404
            ];
            return response()->json($response, 200);
        }
    }
}
