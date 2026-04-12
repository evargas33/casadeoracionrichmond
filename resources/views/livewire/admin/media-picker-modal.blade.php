<div>
    @if($show)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[60] p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[85vh] flex flex-col">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                    <h2 class="font-semibold text-gray-800">Seleccionar imagen</h2>
                    <button wire:click="close" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>

                {{-- Search --}}
                <div class="px-6 py-3 border-b border-gray-100 flex-shrink-0">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Buscar imagen..."
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        autofocus
                    >
                </div>

                {{-- Grid --}}
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @if($images->isEmpty())
                        <div class="py-16 text-center text-gray-400 text-sm">
                            No se encontraron imágenes.
                        </div>
                    @else
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            @foreach($images as $img)
                                <button
                                    type="button"
                                    wire:click="selectImage('{{ $img->public_url }}')"
                                    class="group relative aspect-square rounded-xl overflow-hidden border-2 border-transparent hover:border-indigo-500 focus:outline-none focus:border-indigo-500 transition-all"
                                    title="{{ $img->name }}"
                                >
                                    <img
                                        src="{{ $img->public_url }}"
                                        alt="{{ $img->alt_text ?? $img->name }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-end">
                                        <p class="w-full px-1.5 py-1 text-[10px] text-white font-medium truncate opacity-0 group-hover:opacity-100 transition-opacity bg-black/40">
                                            {{ $img->name }}
                                        </p>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-4">{{ $images->links() }}</div>
                    @endif
                </div>

            </div>
        </div>
    @endif
</div>
