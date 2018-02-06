@if(isset($breadcrumb))
    <ul>
        @foreach($breadcrumb as $name => $url)
            @if(($loop->last))
                <li class="breadcrumb-active">{{ $name }}</li>
            @else
                <li><a href="{{ $url }}" title="{{ $name }}">{{ $name }}</a></li>
            @endif
        @endforeach
    </ul>
@endif