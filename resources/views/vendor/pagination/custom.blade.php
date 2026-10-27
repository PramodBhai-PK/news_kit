@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-4 py-4 px-2 w-full">
        
        <!-- Mobile Simple Navigation (< sm screens) -->
        <div class="flex justify-between flex-1 sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 cursor-not-allowed rounded-xl shadow-2xs">
                    ← {{ __('पिछला') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-brand-primary hover:text-white dark:hover:bg-brand-primary transition-all shadow-2xs">
                    ← {{ __('पिछला') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-brand-primary hover:text-white dark:hover:bg-brand-primary transition-all shadow-2xs">
                    {{ __('अगला') }} →
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 cursor-not-allowed rounded-xl shadow-2xs">
                    {{ __('अगला') }} →
                </span>
            @endif
        </div>

        <!-- Desktop Full Numbers Navigation (>= sm screens) -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <!-- Results Counter Info -->
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                    <span>{{ __('कुल') }}</span>
                    <span class="font-extrabold text-slate-900 dark:text-white font-mono">{{ $paginator->total() }}</span>
                    <span>{{ __('में से') }}</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $paginator->firstItem() }}</span>
                    <span>-</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $paginator->lastItem() }}</span>
                    <span>{{ __('दिखाए जा रहे हैं') }}</span>
                </p>
            </div>

            <!-- Page Number Buttons -->
            <div>
                <ul class="inline-flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100/80 dark:bg-slate-900/80 border border-slate-200/90 dark:border-slate-800 shadow-inner">
                    
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-slate-300 dark:text-slate-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-slate-600 dark:text-slate-300 hover:text-brand-primary hover:bg-white dark:hover:bg-slate-800 transition-all" aria-label="{{ __('pagination.previous') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-slate-400 font-mono">
                                    {{ $element }}
                                </span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li aria-current="page">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-brand-primary text-white font-black text-xs shadow-md shadow-brand-primary/25 font-mono">
                                            {{ $page }}
                                        </span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-slate-700 dark:text-slate-300 hover:text-brand-primary hover:bg-white dark:hover:bg-slate-800 font-bold text-xs transition-all font-mono" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-slate-600 dark:text-slate-300 hover:text-brand-primary hover:bg-white dark:hover:bg-slate-800 transition-all" aria-label="{{ __('pagination.next') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </li>
                    @else
                        <li aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-slate-300 dark:text-slate-600 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </li>
                    @endif

                </ul>
            </div>
        </div>

    </nav>
@endif
