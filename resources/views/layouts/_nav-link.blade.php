<a class="nav-link {{ $item['is_active'] ? 'active' : '' }}" href="{{ route($item['route']) }}">
    <i class="bi {{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
</a>
