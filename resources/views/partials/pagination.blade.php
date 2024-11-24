<nav class="flex justify-center">
    <ul class="pagination">
        @if ($paginator->onFirstPage()) 
            <li class="page-item inactive"> 
                <a tabindex="-1">Previous</a> 
            </li> 
        @else
            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() }}">Previous</a> 
            </li> 
        @endif 
    
        @foreach ($elements as $element) 
            @if (is_string($element)) 
                <li class="page-item inactive">
                    <a>{{ $element }}</a>
                </li> 
            @endif 
    
            @if (is_array($element)) 
            @foreach ($element as $page => $url) 
                @if ($page == $paginator->currentPage()) 
                    <li class="page-item active"> 
                        <a>{{ $page }}</a> 
                    </li> 
                @else 
                    <li class="page-item"> 
                        <a href="{{ $url }}">{{ $page }}</a> 
                    </li> 
                @endif 
            @endforeach
            @endif
        @endforeach 
    
        @if ($paginator->hasMorePages()) 
            <li class="page-item"> 
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a> 
            </li> 
        @else 
            <li class="page-item inactive"> 
                <a>Next</a> 
            </li> 
        @endif 
    </ul> 
</nav>