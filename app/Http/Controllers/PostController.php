<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\File;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Buglinjo\LaravelWebp\Facades\Webp;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all()->map(function ($data) {
            $data->status = $data->status;
            $data->author = $data->user->name;
            return $data;
        });
        return Inertia::render('Post/Index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all()->map(function($data) {
            return [
                'id' => $data->id,
                'label' => $data->name,
            ];
        });
        return Inertia::render('Post/Create', ['tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $param = $request->all();
        $param['user_id'] = Auth::id();
        $param['active'] = $param['active']?? FALSE;

        $file = $request->file('file_id');
        $fileName = $request->file('file_id')->getClientOriginalName();
        $extension = $file->extension();
        $request->file_id->move(public_path('document'), time() . '.' . $extension);

        $fid = File::create([
            'original_name' => $fileName,
            'generated_name' => 'document/'. time() . '.' . $extension
        ]);

        $param['file_id'] = $fid->id;

        Post::create($param);

        return redirect()->route('admin.posts.index')->with('message', 'Post has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $post->asset = $post->file->original_name;
        $tags = Tag::all()->map(function($data) {
            return [
                'id' => $data->id,
                'label' => $data->name,
            ];
        });
        return Inertia::render('Post/Edit', ['tags' => $tags, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $param = $request->all();
        $param['active'] = $param['active']?? FALSE;
        $param['file_id'] = $param['file_id']?? $post->file_id;
        if(!empty($request->file_id)) {
            $file = $request->file('file_id');
            $fileName = $request->file('file_id')->getClientOriginalName();
            $extension = $file->extension();
            $request->file_id->move(public_path('document'), time() . '.' . $extension);

            $fid = File::create([
                'original_name' => 'document/'.$fileName,
                'generated_name' => 'document/'. time() . '.' . $extension
            ]);

            $param['file_id'] = $fid->id;
        }

        $post->update($param);

        return redirect()->route('admin.posts.index')->with('message', 'Post has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        foreach($post->comments as $comment) {
            $comment->replies()->delete();
        }
        $post->comments()->delete();
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', 'Post has been deleted');
    }
}
