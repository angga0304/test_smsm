<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Jobs\ProcessExport;

class FileController extends Controller
{
    public $file;

    public function __construct(File $file)
    {
        $this->file = new File;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = $this->file->getIndexData();
        return Inertia::render('File/Index', [
            'files' => $files,
            'can' => Auth::user()->can('file moderate')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Auth::user()->can('file moderate')) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }
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
        if(!Auth::user()->can('file moderate')) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }
        if ($file->activities->count() > 0) {
            $file->activities = $file->activities;
        }
        $file->asset = $file->asset;
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

    /**
     * Save history activity.
     */
    public function audit(File $file, String $action)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'uuid' => $file->id,
            'model' => 'file',
            'action' => $action,
            'notes' => "$action File",
        ]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        $data = [
            'user' => Auth::id(),
            'type' => 'file'
        ];
        dispatch(new ProcessExport($data));
        return redirect()->route('admin.files.index')->with('message', 'Your excel been queue look in file tab');
    }
}
