<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use Inertia\Inertia;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::all()->map(function ($data) {
            $data->canDelete = $data->Post->count() < 1;
            $data->used = $data->Post->count();
            $data->asset = $data->asset;
            return $data;
        });
        return Inertia::render('File/Index', ['files' => $files])->with('message', 'File has been created');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('File/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        $file = $request->file('file_id');
        $fileName = $request->file('file_id')->getClientOriginalName();
        $extension = $file->extension();
        $request->file_id->move(public_path('document'), time() . '.' . $extension);

        $fid = File::create([
            'original_name' => $fileName,
            'generated_name' => 'document/'. time() . '.' . $extension
        ]);

        return redirect()->route('admin.files.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        return Inertia::render('File/Edit', ['file' => $file]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        if(!empty($request->file_id)) {
            $files = $request->file('file_id');
            $fileName = $request->file('file_id')->getClientOriginalName();
            $extension = $files->extension();
            $request->file_id->move(public_path('document'), time() . '.' . $extension);

            $param = [
                'original_name' => $fileName,
                'generated_name' => 'document/'. time() . '.' . $extension
            ];
            $file->update($param);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $file->delete();
        return redirect()->route('admin.files.index')->with('message', 'File has been deleted');
    }
}
