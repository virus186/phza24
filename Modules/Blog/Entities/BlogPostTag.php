<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPostTag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = "blog_post_tag";
    
    
}
