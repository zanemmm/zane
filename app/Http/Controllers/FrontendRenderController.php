<?php

namespace App\Http\Controllers;

use App\Http\Services\FrontendRenderService as Render;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class FrontendRenderController extends Controller
{
    public function index(Render $render)
    {
        $posts = Post::query()->with(['category', 'tags'])
            ->where('publish', 1)
            ->orderByDesc('created_at')
            ->paginate(config('site.pagination', 5));
        return $render->main('list', compact('posts'));
    }

    public function search(Render $render, $keyword)
    {
        $posts = Post::search($keyword)
            ->with(['category', 'tags'])
            ->where('publish', 1)
            ->paginate(config('site.pagination', 5));
        $title = "搜索关键词为: $keyword 的文章 - " . config('site.name');
        if ($posts->total() === 0) {
            $status = "找不到包含 $keyword 关键字的文章!";
            $message = '请修改关键字后再重新搜索.';
            return $render->title($title)->main('error', compact('title', 'status', 'message'));
        }
        return $render->title($title)
            ->main('list', compact('posts'));
    }

    public function category(Category $category, Render $render)
    {
        $posts = $category->posts()
            ->where('publish', 1)
            ->orderByDesc('created_at')
            ->paginate(config('site.pagination', 5));
        return $render->main('list', compact('posts'));
    }

    public function post(Post $post, Render $render)
    {
        if (!$post->publish) {
            abort(404);
        }
        return $render->main('post', compact('post'));
    }

    public function tag(Tag $tag, Render $render)
    {
        $posts = $tag->posts()
            ->where('publish', 1)
            ->orderByDesc('created_at')
            ->paginate(config('site.pagination', 5));
        return $render->main('list', compact('posts'));
    }
}
