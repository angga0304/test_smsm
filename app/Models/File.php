<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use Illuminate\Support\Str;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'original_name',
        'generated_name'
    ];

    public function Post() {
        return $this->hasMany(Post::class, 'file_id');
    }

    public function getAssetAttribute() {
        return '<a 
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2" 
        download="document_'. $this->original_name .'"
        href="'. asset($this->generated_name) .'">Download</a>';
    }

    public function getNameAttribute() {
        return $this->original_name;
    }

    public function getActivitiesAttribute() {
        $activities = Activity::where('model', 'file')
            ->where('uuid', $this->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($data) {
                return (object)[
                    'time' => $data->created_at->format('Y/m/d H:i:s'),
                    'action' => $data->notes,
                    'user' => $data->author,
                    'note' => $data->notes,
                ];
            });
        return $activities;
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
