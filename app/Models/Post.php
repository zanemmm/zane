<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Masterminds\HTML5;
use Nicolaslopezj\Searchable\SearchableTrait;

class Post extends Model
{
    use SoftDeletes, SearchableTrait;

    protected $dates = ['deleted_at'];

    protected $searchable = [
        'columns' => [
            'posts.title' => 10,
            'posts.content' => 5
        ]
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'post_tag', 'post_id', 'tag_id');
    }

    public function seoTitle()
    {
        return $this->title . ' - ' . config('site.name');
    }

    public function breadcrumb()
    {
        if (isset($this->category)) {
            $categoryName = $this->category->name;
            $categoryUrl = $this->category->url();
        } else {
            $categoryName = '暂无分类';
            $categoryUrl = url('/');
        }
        return [
            '首页' => url('/'),
            $categoryName => $categoryUrl,
            $this->title => $this->url()
        ];
    }

    public function url()
    {
        return url('posts/' . $this->slug);
    }

    public static function getSummary(string $html, int $length, bool $strict = false) {
        $n = 0;
        $result = '';
        $html5 = new HTML5();
        $dom = $html5->loadHTML($html);
        foreach ($dom->lastChild->childNodes as $node) {
            $n += mb_strlen($node->textContent, 'utf-8');
            if ($n > $length) {
                if (!$strict) {
                    $result .= $dom->saveHTML($node);
                }
                break;
            }
            $result .= $dom->saveHTML($node);
        }
        return $result;
    }

    public static function getDescription(string $summary)
    {
        $html5 = new HTML5();
        $dom = $html5->loadHTML($summary);
        $text = $dom->textContent;
        $text = str_replace(["\r\n", "\r", "\n"], '', $text);
        return mb_strcut($text, 0, 150) . '...';
    }
}
