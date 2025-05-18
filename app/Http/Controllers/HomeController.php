<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display homepage.
     */
    public function index()
    {
        $posts = Post::all()->sortByDesc('created_at')->map(function ($post) {
            $post->author = $post->user->name;
            $post->tag_name = $post->tag->name;
            $post->link = "/post/$post->slug";
            return $post;
        });
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'posts' => $posts,
        ]);
    }

    /**
     * Display detail post.
     */
    public function detail(String $slug){
        $uid = Auth::user();
        $post = Post::findBySlugOrFail($slug);
        $post->comments = $post->listcommentfront->map(function ($comment) {
            $comment->author = $comment->user->name;
            $comment->timeline = $comment->created_at->diffForHumans(Carbon::now());
            return $comment;
        })->toArray();
        $post->author = $post->user->name;
        $post->asset = $post->asset;
        $post->tag_name = $post->tag->name;
        $post->story = json_decode($post->story);
        return Inertia::render('Detail', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'post' => $post,
            'uid' => $uid,
        ]);
    }
}
