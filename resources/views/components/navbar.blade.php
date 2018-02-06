<ul>
    @if($url == url('/'))
        <li class="nav-active"><a href="{{ url('/') }}">首页</a></li>
    @else
        <li><a href="{{ url('/') }}">首页</a></li>
    @endif
    @foreach($categories as $category)
        @if($url == $category->url())
            <li class="nav-active">{{ $category->name }}</li>
        @else
            <li><a href="{{ $category->url() }}">{{ $category->name }}</a></li>
        @endif
    @endforeach
    <li class="search">
        <input title="搜索" type="text" name="search" placeholder="搜索">
        <span class="fa fa-search"></span>
    </li>
</ul>