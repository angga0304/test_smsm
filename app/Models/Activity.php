<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Activity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'uuid',
        'model',
        'notes',
        'action',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAuthorAttribute() {
        return $this->user->name;
    }
}
