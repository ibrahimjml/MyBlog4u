<?php

namespace App\Models;


use App\Builders\PostBuilder;
use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Post extends Model
{
  use HasFactory,Searchable;

  protected $fillable = [
    'title',
    'slug',
    'short_excerpt',
    'description',
    'status',
    'user_id',
    'image_path',
    'is_featured',
    'is_pinned',
    'pinned_at',
    'is_reported',
    'report_count',
    'likes_count',
    'views',
    'allow_comments',
    'published_at',
    'banned_at',
    'trashed_at'
  ];
  protected $attributes = [
    'status' => PostStatus::PUBLISHED->value,
  ];
   protected $casts = [
        'is_pinned' => 'boolean',
        'pinned_at' => 'datetime',  
        'published_at' => 'datetime',  
        'banned_at' => 'datetime',  
        'trashed_at' => 'datetime',  
        'status' => PostStatus::class
    ];
  public function newEloquentBuilder($query): PostBuilder
    {
        return new PostBuilder($query);
    }
  public function user()
  {  
    return $this->belongsTo(User::class, 'user_id');
  }

  public function likes(){
    return $this->hasMany(Like::class);
  }
  public function hashtags(){
    return $this->belongsToMany(Hashtag::class,'post_hashtag')
                 ->where('status',\App\Enums\TagStatus::ACTIVE);
  }
    public function allHashtags(){
    return $this->belongsToMany(Hashtag::class,'post_hashtag');
  }
  public function categories()
  {
    return $this->belongsToMany(Category::class,'post_category');
  }
  public function views()
  {
    return $this->hasMany(ProfileView::class);
  }
  public function viewers()
  {
    return $this->belongsToMany(User::class,'post_views','post_id','viewer_id');
  }
  public function reports()
  {
    return $this->hasMany(PostReport::class);
  }
  public function toSearchableArray()
  {
    return [
      'id' =>$this->id,
      'title'=>$this->title,
      'description'=>$this->description,
      'hashtags' => $this->hashtags->pluck('name')->implode(' '),
    ];
  }

  public function is_liked(){
    return $this->likes()->where('user_id',auth()->user()->id)->exists();
  }

  public function scopeFeatured($query)
  {
    return  $query->where('is_featured', true);
  }
  public function scopeEnabled($query)
  {
    return  $query->where('allow_comments', true);
  }
  public function scopeStatus($query, $status)
  {
    return  $query->where('status', $status);
  }
  public function scopePublished($query)
  {
    return  $query->where('status', PostStatus::PUBLISHED->value);
  }
  public function scopeDraft($query)
  {
    return  $query->where('status', PostStatus::DRAFT->value);
  }
  public function scopePending($query)
  {
    return  $query->where('status', PostStatus::PENDING->value);
  }
  public function getImageUrlAttribute()
{
    return $this->image_path 
        ? Storage::url( 'uploads/' .$this->image_path)
        : Storage::url('covers/sunset.jpg');
}
  public function comments(){
    return $this->hasMany(Comment::class)
                ->whereNull('parent_id')
                ->orderBy('created_at','DESC');
  }
  
  public function totalcomments(){
    return $this->hasMany(Comment::class, 'post_id');
  }


}
