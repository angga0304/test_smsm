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
    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
    /**
     * Display homepage.
     */
    public function index()
    {
        $posts = $this->post->getListFrontEnd();
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
        $post = $this->post->getDetailFrontEnd($slug);
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
