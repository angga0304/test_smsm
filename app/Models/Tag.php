<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Activity;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    public function posts() {
        return $this->hasMany(Post::class, 'tag_id');
    }

    public function getOptionData() {
        return $this->all()->map(function($data) {
            return [
                'id' => $data->id,
                'label' => $data->name,
            ];
        });
    }

    public function getActivitiesAttribute() {
        $activities = Activity::where('model', 'tag')
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

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
