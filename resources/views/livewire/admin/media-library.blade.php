<div class="space-y-4">

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Upload zone --}}
    @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']))
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Subir archivos</h2>

        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf

            <label
                for="media-upload"
                x-data="{ dragging: false, count: 0 }"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="
                    dragging = false;
                    $refs.input.files = $event.dataTransfer.files;
                    count = $event.dataTransfer.files.length;
                "
                :class="dragging ? 'border-[#c9a84c] bg-[#c9a84c]/5' : 'border-gray-300 hover:border-gray-400'"
                class="block border-2 border-dashed rounded-lg p-8 text-center transition-colors cursor-pointer">

                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
                <p class="text-sm text-gray-400">
                    Arrastra archivos aquí o <span class="text-[#c9a84c] font-medium">haz clic para seleccionar</span>
                </p>
                <p class="text-xs text-gray-300 mt-1">JPG, PNG, GIF, WebP, SVG, MP3, WAV, MP4 — máx. 50 MB por archivo</p>

                <input
                    x-ref="input"
                    id="media-upload"
                    type="file"
                    name="files[]"
                    multiple
                    accept="image/*,audio/*,video/mp4,video/quicktime"
                    class="sr-only"
                    @change="count = $event.target.files.length"
                >

                <p x-show="count > 0" x-text="count + ' archivo(s) seleccionado(s)'"
                    class="mt-3 text-sm font-medium text-[#1a2e4a]"></p>
            </label>

            @if($errors->any())
                <ul class="mt-2 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-red-500 text-xs">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-3 flex justify-end">
                <button
                    type="submit"
                    class="px-4 py-1.5 bg-[#1a2e4a] text-white text-sm rounded-lg hover:bg-[#c9a84c] transition-colors">
                    Subir a la biblioteca
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 items-center">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Buscar archivo..."
            class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c] w-56"
        >

        <div class="flex gap-1">
            @foreach([''=>'Todos', 'image'=>'Imágenes', 'audio'=>'Audio', 'video'=>'Video'] as $val => $label)
                <button
                    wire:click="$set('filterType', '{{ $val }}')"
                    class="px-3 py-1.5 text-xs rounded-lg border transition-colors
                        {{ $filterType === $val
                            ? 'bg-[#1a2e4a] text-white border-[#1a2e4a]'
                            : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <span class="text-xs text-gray-400 ml-auto">{{ $mediaItems->total() }} archivos</span>
    </div>

    {{-- Grid --}}
    @if($mediaItems->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3">
            @foreach($mediaItems as $item)
                <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">

                    {{-- Preview --}}
                    <div class="aspect-square bg-gray-50 flex items-center justify-center relative">
                        @if($item->type === 'image')
                            <img
                                src="{{ $item->public_url }}"
                                alt="{{ $item->alt_text ?? $item->name }}"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            >
                        @elseif($item->type === 'audio')
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009 15.553z"/>
                                </svg>
                                <span class="text-[10px] text-gray-400 uppercase font-medium">Audio</span>
                            </div>
                        @elseif($item->type === 'video')
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                                <span class="text-[10px] text-gray-400 uppercase font-medium">Video</span>
                            </div>
                        @else
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                                <span class="text-[10px] text-gray-400 uppercase font-medium">Archivo</span>
                            </div>
                        @endif

                        {{-- Actions overlay --}}
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            {{-- Copy URL --}}
                            <button
                                type="button"
                                title="Copiar URL"
                                onclick="navigator.clipboard.writeText('{{ $item->public_url }}').then(() => { this.title = '¡Copiado!' })"
                                class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>

                            {{-- Open in new tab --}}
                            <a
                                href="{{ $item->public_url }}"
                                target="_blank"
                                title="Ver archivo"
                                class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>

                            {{-- Delete --}}
                            @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                            <form method="POST"
                                action="{{ route('admin.media.destroy', $item) }}"
                                onsubmit="return confirm('¿Eliminar este archivo?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Eliminar"
                                    class="w-8 h-8 rounded-full bg-red-500/70 hover:bg-red-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Meta --}}
                    <div class="px-2 py-2">
                        <p class="text-xs font-medium text-gray-700 truncate" title="{{ $item->name }}">{{ $item->name }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            {{ $item->human_size }}
                            @if($item->width) · {{ $item->width }}×{{ $item->height }}px @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div>{{ $mediaItems->links() }}</div>

    @else
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
            </svg>
            <p class="text-sm text-gray-400">No hay archivos en la biblioteca</p>
        </div>
    @endif

</div>
