@php
    $user = auth()->user();

    // Keep only items the user is allowed to see.
    $visible = collect(config('menu', []))->map(function ($section) use ($user) {
        $section['items'] = collect($section['items'])
            ->filter(fn ($item) => empty($item['can']) || ($user && $user->can($item['can'])))
            ->map(function ($item) {
                $item['active'] = $item['active'] ?? $item['route'];
                $item['is_active'] = request()->routeIs($item['active']);
                return $item;
            })
            ->values();
        return $section;
    })->filter(fn ($section) => $section['items']->isNotEmpty())->values();
@endphp

<nav class="nav flex-column flex-grow-1 overflow-auto">
    @foreach ($visible as $i => $section)
        @php $sectionActive = $section['items']->contains('is_active', true); @endphp

        @if (empty($section['label']))
            {{-- Top-level items, no header --}}
            @foreach ($section['items'] as $item)
                @include('layouts._nav-link', ['item' => $item])
            @endforeach
        @elseif (! ($section['collapsible'] ?? true))
            <div class="text-uppercase small text-secondary px-2 mt-3 mb-1">{{ $section['label'] }}</div>
            @foreach ($section['items'] as $item)
                @include('layouts._nav-link', ['item' => $item])
            @endforeach
        @else
            @php $collapseId = 'nav-section-' . $i; @endphp
            <button class="btn btn-link nav-link d-flex justify-content-between align-items-center text-uppercase small text-secondary px-2 mt-2 mb-1 text-decoration-none"
                    data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                    aria-expanded="{{ $sectionActive ? 'true' : 'false' }}">
                <span>{{ $section['label'] }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <div class="collapse {{ $sectionActive ? 'show' : '' }}" id="{{ $collapseId }}">
                @foreach ($section['items'] as $item)
                    @include('layouts._nav-link', ['item' => $item])
                @endforeach
            </div>
        @endif
    @endforeach
</nav>
