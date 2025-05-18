<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Activity;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TagExports;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return Inertia::render('Tag/Index', ['tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        return Excel::download(new TagExports, 'tag_'. time() .'.xlsx');
    }
}
