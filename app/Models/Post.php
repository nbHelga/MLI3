<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    //
    // public static function find($slug): array{
    //     //using 'static' because this function use 'static'
        
    //     //return Arr::first(static::all(), function($post) use ($slug)
    //     //{return $post['slug'] == $slug;});

    //     $post = Arr::first(static::all(), fn($post) => $post['slug'] == $slug);
    //     if(! $post){
    //         abort(404);
    //     }
    //     return $post;
    // }

    // protected $table = 'blog_posts';
    // protected $primaryKey = 'post_id';
    use HasFactory;
    protected $fillable = ['title', 'author', 'slug', 'body'];

    protected $with =['author', 'category'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    
}
