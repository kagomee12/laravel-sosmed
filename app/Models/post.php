<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function children()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
