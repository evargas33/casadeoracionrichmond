<div class="space-y-4">

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2.5 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <div class="flex gap-3 flex-wrap">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar categoría..."
                class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c] w-48"
            >

            <div class="flex gap-1">
                @foreach(['' => 'Todas', 'event' => 'Eventos', 'page' => 'Páginas'] as $val => $label)
                    <button wire:click="$set('filterType', '{{ $val }}')"
                        class="px-3 py-1.5 text-xs rounded-lg border transition-colors
                            {{ $filterType === $val
                                ? 'bg-[#1a2e4a] text-white border-[#1a2e4a]'
                                : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']))
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-1.5 bg-[#1a2e4a] text-white text-sm rounded-lg hover:bg-[#c9a84c] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva categoría
        </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Uso</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50 transition-colors">

                    {{-- Name + color --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full flex-shrink-0"
                                style="background-color: {{ $cat->color }}"></div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $cat->name }}</p>
                                <p class="text-xs text-gray-400">{{ $cat->slug }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Type --}}
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-0.5 text-xs rounded-full font-medium
                            {{ $cat->type === 'event' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $cat->type === 'event' ? 'Evento' : 'Página' }}
                        </span>
                    </td>

                    {{-- Usage count --}}
                    <td class="px-4 py-3 text-center">
                        @if($cat->type === 'event')
                            <span class="text-sm font-medium text-gray-600">{{ $cat->events_count }}</span>
                            <span class="text-xs text-gray-400"> eventos</span>
                        @else
                            <span class="text-sm font-medium text-gray-600">{{ $cat->pages_count }}</span>
                            <span class="text-xs text-gray-400"> páginas</span>
                        @endif
                    </td>

                    {{-- Estado --}}
                    <td class="px-4 py-3">
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                            <button wire:click="toggleActive({{ $cat->id }})"
                                class="px-2.5 py-0.5 text-xs rounded-full font-medium transition-colors
                                    {{ $cat->active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                {{ $cat->active ? 'Activa' : 'Inactiva' }}
                            </button>
                        @else
                            <span class="px-2.5 py-0.5 text-xs rounded-full font-medium
                                {{ $cat->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ $cat->active ? 'Activa' : 'Inactiva' }}
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2 justify-end">
                            @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']))
                            <button wire:click="openEdit({{ $cat->id }})"
                                class="p-1.5 text-gray-400 hover:text-[#1a2e4a] hover:bg-gray-100 rounded-md transition-colors"
                                title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            @endif

                            @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                            <button
                                wire:click="delete({{ $cat->id }})"
                                wire:confirm="¿Eliminar la categoría '{{ $cat->name }}'?"
                                class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors"
                                title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400">
                        No se encontraron categorías.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $categories->links() }}</div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" wire:click="$set('showModal', false)"></div>

        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-800">
                    {{ $isEditing ? 'Editar categoría' : 'Nueva categoría' }}
                </h2>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="save" class="px-5 py-4 space-y-4">

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                    <input wire:model.live="name" type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c]">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Slug</label>
                    <input wire:model="slug" type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c] font-mono">
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                    <select wire:model="type"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c]"
                        {{ $isEditing ? 'disabled' : '' }}>
                        <option value="event">Evento</option>
                        <option value="page">Página</option>
                    </select>
                    @if($isEditing)
                        <p class="text-xs text-gray-400 mt-1">El tipo no se puede cambiar una vez creada.</p>
                    @endif
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Color + preview --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Color</label>
                    <div class="flex items-center gap-3">
                        <input wire:model.live="color" type="color"
                            class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                        <input wire:model.live="color" type="text"
                            class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-1 focus:ring-[#c9a84c]"
                            placeholder="#1a2e4a">
                        <span class="px-3 py-1 rounded-full text-xs font-medium text-white"
                            style="background-color: {{ $color }}">
                            {{ $name ?: 'Preview' }}
                        </span>
                    </div>
                    @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Descripción <span class="text-gray-300">(opcional)</span></label>
                    <textarea wire:model="description" rows="2"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c] resize-none"></textarea>
                </div>

                {{-- Active --}}
                <div class="flex items-center gap-2">
                    <input wire:model="active" type="checkbox" id="cat_active"
                        class="rounded border-gray-300 text-[#1a2e4a] focus:ring-[#c9a84c]">
                    <label for="cat_active" class="text-sm text-gray-700 cursor-pointer">Categoría activa</label>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-1.5 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-1.5 text-sm bg-[#1a2e4a] text-white rounded-lg hover:bg-[#c9a84c] transition-colors">
                        {{ $isEditing ? 'Actualizar' : 'Crear' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
