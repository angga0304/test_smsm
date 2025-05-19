<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Activity;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessExport;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return Inertia::render('Tag/Index', [
            'tags' => $tags,
            'can' => Auth::user()->can('tag moderate')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Auth::user()->can('tag moderate')) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }
        return Inertia::render('Tag/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $param = $request->all();
        $tag = Tag::create($param);
        $this->audit($tag, 'Create');

        return redirect()->route('admin.tag.index')->with('message', 'Tag has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        if(!Auth::user()->can('tag moderate')) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }
        if ($tag->activities->count() > 0) {
            $tag->activities = $tag->activities;
        }
        return Inertia::render('Tag/Edit', ['tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $param = $request->all();
        $param['active'] = $param['active']?? FALSE;
        $tag->update($param);
        $this->audit($tag, 'Update');

        return redirect()->route('admin.tag.index')->with('message', 'Tag has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        if ($tag->posts->count() > 0) {
            return redirect()->route('admin.tag.index')->with('message', 'Tag cant deleted used by post');
        }
        $this->audit($tag, 'Delete');
        $tag->delete();
        return redirect()->route('admin.tag.index')->with('message', 'Tag has been deleted');
    }

    /**
     * Save history activity.
     */
    public function audit(Tag $tag, String $action)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'uuid' => $tag->id,
            'model' => 'tag',
            'action' => $action,
            'notes' => "$action Tag",
        ]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        $data = [
            'user' => Auth::id(),
            'type' => 'tag'
        ];
        dispatch(new ProcessExport($data));
        return redirect()->route('admin.files.index')->with('message', 'Your excel been queue look in file tab');
    }
}
