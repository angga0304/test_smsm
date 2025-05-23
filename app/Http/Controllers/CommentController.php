<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(String $slug, StoreCommentRequest $request)
    {
        $post = Post::findBySlugOrFail($slug);
        $param = $request->all();
        $param['user_id'] = Auth::id();
        $post->comments()->create($param);
        return redirect()->route('post.detail', $slug);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $post = $comment->post->slug;
        $comment->blocked = !$comment->blocked;
        $comment->save();
        $this->audit($comment->post, 'Moderate');
        return redirect()->back();
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
            'notes' => "Moderate comment",
        ]);
    }
}
