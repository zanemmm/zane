<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class BackendRenderController extends Controller
{
    public function index()
    {
        $posts = Post::query()->with(['category', 'tags'])
            ->where('publish', 1)
            ->orderByDesc('created_at')
            ->paginate(config('site.pagination', 5));
        $title = config('site.title', "Zane's Blog");
        return view('list', compact('posts', 'title'));
    }

    public function search($keyword)
    {
        $posts = Post::search($keyword)
            ->with(['category', 'tags'])
            ->where('publish', 1)
            ->paginate(config('site.pagination', 5));
        $title = "搜索关键词为: $keyword 的文章 - " . config('site.name');
        if ($posts->total() === 0) {
            $status = "找不到包含 $keyword 关键字的文章!";
            $message = '请修改关键字后再重新搜索.';
            return response()
                ->view('error', compact('title', 'status', 'message'))
                ->setStatusCode(404);
        }
        return view('list', compact('posts', 'title'));
    }

    public function category(Category $category)
    {
        $posts = $category->posts()
            ->where('publish', 1)
            ->orderByDesc('created_at')
            ->paginate(config('site.pagination', 5));
        return view('list', compact('posts'));
    }

    public function post(Post $post)
    {
        if (!$post->publish) {
            abort(404);
        }
        return view('post', compact('post'));
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()
            ->where('publish', 1)
            ->orderByDesc('created_at')
            ->paginate(config('site.pagination', 5));
        return view('list', compact('posts'));
    }
}
