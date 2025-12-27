@props(['column', 'label', 'currentSort' => null, 'currentDirection' => 'asc'])

@php
    $isActive = $currentSort === $column;
    $nextDirection = ($isActive && $currentDirection === 'asc') ? 'desc' : 'asc';
    $params = array_merge(request()->query(), ['sort' => $column, 'direction' => $nextDirection]);
@endphp

<a href="{{ request()->url() }}?{{ http_build_query($params) }}"
   class="flex items-center gap-1 hover:text-success-300 transition-colors">
    {{ $label }}
    <span class="text-xs">
        @if($isActive)
            @if($currentDirection === 'asc')
                <i class="fa fa-sort-up"></i>
            @else
                <i class="fa fa-sort-down"></i>
            @endif
        @else
            <i class="fa fa-sort text-bgray-400"></i>
        @endif
    </span>
</a>
