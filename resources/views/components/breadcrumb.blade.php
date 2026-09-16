<nav class="breadcrumb">
    <a href="{{ url('/') }}">Home</a>
    @foreach($crumbs as $crumb)
        <span class="breadcrumb__separator">></span>
        @if($loop->last)
            <span class="breadcrumb__current">{{ $crumb['label'] }}</span>
        @else
            <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
        @endif
    @endforeach
</nav>
