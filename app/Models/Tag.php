<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts()
    {
        return $this->belongsToMany('App\Models\Post', 'post_tag', 'tag_id', 'post_id');
    }

    public function seoTitle()
    {
        return $this->title ?? '标签: ' . $this->name . ' 的文章 - ' . config('site.name');
    }

    public function breadcrumb()
    {
        return [
            '首页' => url('/'),
            $this->name => $this->url()
        ];
    }

    public function url()
    {
        return url('tags/' . $this->slug);
    }
}
