<?php

namespace App\Http\Controllers;

use App\Exports\PostExport;
use App\Models\Post;
use App\Models\Tag;
use App\Models\File;
use App\Models\Activity;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Jobs\ProcessExport;

class PostController extends Controller
{
    public $tag, $post;

    public function __construct(Tag $tag, Post $post)
    {
        $this->tag = new Tag;
        $this->post = new Post;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = $this->post->getIndexData();
        return Inertia::render('Post/Index', [
            'posts' => $posts,
            'can' => Auth::user()->can('post moderate')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Auth::user()->can('post moderate')) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }
        $tags = $this->tag->getOptionData();
        return Inertia::render('Post/Create', ['tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $param = $request->all();
        $param['story'] = json_encode(preg_split('/\r\n|\r|\n/', $param['story']));
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

        $post = Post::create($param);
        $this->audit($post, 'Create');

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
        if(!Auth::user()->can('post moderate')) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }
        if ($post->activities->count() > 0) {
            $post->activities = $post->activities;
        }
        $post->story = implode(PHP_EOL, json_decode($post->story));
        $post->asset = $post->file->original_name;
        $post->comments = $post->getListcomments();
        $tags = $this->tag->getOptionData();
        return Inertia::render('Post/Edit', ['tags' => $tags, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $param = $request->all();
        $param['story'] = json_encode(preg_split('/\r\n|\r|\n/', $param['story']));
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
        $this->audit($post, 'Edit');

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
        $this->audit($post, 'Delete');
        $post->comments()->delete();
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', 'Post has been deleted');
    }

    /**
     * Save history activity.
     */
    public function audit(Post $post, String $action)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'uuid' => $post->id,
            'model' => 'post',
            'action' => $action,
            'notes' => "$action Post",
        ]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        // return Excel::download(new PostExport, 'post.xlsx');
        $data = [
            'user' => Auth::id(),
            'type' => 'post'
        ];
        dispatch(new ProcessExport($data));
        return redirect()->route('admin.files.index')->with('message', 'Your excel been queue look in file tab');
    }
}
