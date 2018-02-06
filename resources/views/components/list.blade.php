@foreach($posts as $post)
    <article class="card">
        <header>
            <h3 class="title"><a href="{{ url('posts/' . $post->slug) }}" title="{{ $post->title }}">{{ $post->title }}</a></h3>
            <div class="info">
                <span><i class="fa fa-folder"></i>{{ $post->category->name ?? '暂无分类' }}</span>
                <span><i class="fa fa-calendar"></i>{{ $post->created_at->format('Y-m-d') }}</span>
                <span><i class="fa fa-user"></i>{{ config('site.author', 'zane') }}</span>
            </div>
        </header>
        <div class="content">
            {!! $post->summary !!}
        </div>
        <div class="read-more">
            <a class="icon-next" href="{{ $post->url() }}" title="阅读全文>>{{ $post->title }}<<阅读全文"><span>&dArr;</span></a>
        </div>
    </article>
@endforeach
{{ $posts->links('components.pagination') }}