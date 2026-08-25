@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman Katalog" class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white px-5 py-4 rounded-2xl border border-slate-200 shadow-sm">
        
        <!-- Counter info -->
        <div class="text-xs text-slate-500 font-medium text-center sm:text-left">
            Menampilkan <span class="font-bold text-slate-800">{{ $paginator->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $paginator->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $paginator->total() }}</span> produk
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center gap-1.5 flex-wrap justify-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center px-3.5 py-2 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed select-none">
                    <i data-lucide="chevron-left" class="w-4 h-4 mr-1"></i> Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-blue-50 hover:text-blue-700 border border-slate-200 hover:border-blue-300 rounded-xl transition shadow-xs">
                    <i data-lucide="chevron-left" class="w-4 h-4 mr-1"></i> Sebelumnya
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-slate-400 select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center w-8 h-8 text-xs font-extrabold text-white bg-blue-700 rounded-xl shadow-sm select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold text-slate-700 bg-white hover:bg-blue-50 hover:text-blue-700 border border-slate-200 hover:border-blue-300 rounded-xl transition shadow-xs">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-blue-50 hover:text-blue-700 border border-slate-200 hover:border-blue-300 rounded-xl transition shadow-xs">
                    Selanjutnya <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </a>
            @else
                <span class="inline-flex items-center justify-center px-3.5 py-2 text-xs font-semibold text-slate-300 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed select-none">
                    Selanjutnya <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
