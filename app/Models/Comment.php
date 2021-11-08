<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['tier','name','comment','parent_id','post_id'];

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
