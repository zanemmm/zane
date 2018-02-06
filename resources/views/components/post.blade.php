<article id="post" class="card">
    <header>
        <h1 class="title">{{ $post->title }}</h1>
        <div class="info">
            <span><i class="fa fa-folder"></i>{{ $post->category->name ?? '暂无分类' }}</span>
            <span><i class="fa fa-calendar"></i>{{ $post->created_at->format('Y-m-d') }}</span>
            <span><i class="fa fa-user"></i>{{ config('site.author', 'zane') }}</span>
        </div>
    </header>
    <div class="toc"></div>
    <div class="content">
        {!! $post->html !!}
        <div class="footer-info">
            @if(!empty($post->tags->count()))
                <p><i class="fa fa-tags"></i>本文标签:
                @foreach($post->tags as $key => $tag)
                    <a class="tag" href="{{ $tag->url() }}" title="{{ $tag->name }}">{{ $tag->name }}</a>
                @endforeach
                </p>
            @endif
            <p>本作品采用 <a rel="license" target="_blank" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">知识共享署名-非商业性使用-禁止演绎 4.0 国际许可协议</a> 进行许可。</p>
            <p>转载请注明出处: <a href="{{ $post->url() }}" title="{{ $post->title }}">{{ config('site.author') . ' - ' . $post->title . ' - ' . config('site.name') }}</a></p>
        </div>
    </div>
</article>