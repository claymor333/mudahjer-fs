@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-sm text-base-content/70 hidden sm:block text-gray-500">
            Showing {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }} records
        </div>
        <div class="join">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="join-item btn btn-disabled btn-sm sm:btn-md">«</button>
            @else
                <a href="{{ $paginator->appends(request()->except('page'))->previousPageUrl() }}" class="join-item btn btn-sm sm:btn-md">«</a>
            @endif

            {{-- Pagination Elements --}}
            @php
                $window = $paginator->lastPage() <= 5 ? 2 : 2; // Reduce window on mobile
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max(1, $currentPage - $window);
                $end = min($lastPage, $currentPage + $window);
            @endphp

            <!-- Show first page only on larger screens -->
            @if($start > 1)
                <a href="{{ $paginator->appends(request()->except('page'))->url(1) }}" class="join-item btn btn-sm sm:btn-md hidden sm:inline-flex">1</a>
                @if($start > 2)
                    <span class="join-item btn btn-disabled btn-sm sm:btn-md hidden sm:inline-flex">...</span>
                @endif
            @endif

            @for($i = $start; $i <= $end; $i++)
                @if($i == $currentPage)
                    <button class="join-item btn btn-soft btn-primary btn-sm sm:btn-md">{{ $i }}</button>
            @else
                    <a href="{{ $paginator->appends(request()->except('page'))->url($i) }}" class="join-item btn btn-sm sm:btn-md">{{ $i }}</a>
                @endif
            @endfor

            <!-- Show last page only on larger screens -->
            @if($end < $lastPage)
                @if($end < $lastPage - 1)
                    <span class="join-item btn btn-disabled btn-sm sm:btn-md hidden sm:inline-flex">...</span>
                @endif
                <a href="{{ $paginator->appends(request()->except('page'))->url($lastPage) }}" class="join-item btn btn-sm sm:btn-md hidden sm:inline-flex">{{ $lastPage }}</a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->appends(request()->except('page'))->nextPageUrl() }}" class="join-item btn btn-sm sm:btn-md">»</a>
            @else
                <button class="join-item btn btn-disabled btn-sm sm:btn-md">»</button>
            @endif
        </div>
    </div>
@else
    <div class="text-sm text-base-content/70 hidden sm:block text-gray-500">
        Showing {{ $paginator->total() }} records
    </div>
@endif