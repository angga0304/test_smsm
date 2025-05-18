<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use App\Models\File;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes, HasUuids;
    use Sluggable, SluggableScopeHelpers;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'tag_id',
        'active',
        'file_id',
        'story',
    ];

    public function tag() {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function getListcommentAttribute() {
        return $this->comments()->orderByDesc('created_at')->get();
    }

    public function getListcommentfrontAttribute() {
        return $this->comments()->where('blocked', FALSE)->orderByDesc('created_at')->get();
    }

    public function getStatusAttribute() {
        return $this->active? 'Active' : 'Archive';
    }

    public function getNameAttribute() {
        return $this->title;
    }

    public function getAssetAttribute() {
        return '<a download="' . $this->file->original_name . '" href="'. asset($this->file->generated_name) .'">' . $this->file->original_name . '</a>';
    }

    public function file() {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function getActionAttribute() {
        return '<a href="/admin/posts' . $this->id . '/edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>';
    }

    public function getActivitiesAttribute() {
        $activities = Activity::where('model', 'post')
            ->where('uuid', $this->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($data) {
                return (object)[
                    'time' => $data->created_at->format('Y/m/d H:i:s'),
                    'action' => $data->action,
                    'user' => $data->author,
                    'note' => $data->notes,
                ];
            });
        return $activities;
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
