<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\HasAccessAdmin;
use App\Http\Middleware\Admin\HandleInertiaAdminRequests;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Models\Post;

Route::get('/', [HomeController::class, 'index']);
Route::get('/post/{slug}', [HomeController::class, 'detail'])->name('post.detail');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth', HasAccessAdmin::class, HandleInertiaAdminRequests::class],
    'as' => 'admin.',
], function () {
    Route::get('tag/export', [TagController::class, 'export'])->name('tag.export');
    Route::get('posts/export', [PostController::class, 'export'])->name('posts.export');
    Route::get('files/export', [FileController::class, 'export'])->name('files.export');
    Route::resource('tag', TagController::class);
    Route::resource('posts', PostController::class);
    Route::resource('files', FileController::class);
    Route::delete('comment/{comment}', [CommentController::class, 'destroy'])->name('comment.delete');

});


Route::post('/post/{slug}/post-comment', [CommentController::class, 'store'])->name('post.comment');

require __DIR__.'/auth.php';
