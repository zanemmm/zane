<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function seoTitle()
    {
        return $this->title ?? '分类: ' . $this->name . ' 的文章 - ' . config('site.name');
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
        return url('categories/' . $this->slug);
    }
}
