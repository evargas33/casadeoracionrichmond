<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Inscripciones</h1>
        <div class="flex items-center gap-3">
            <button wire:click="exportCsv"
                class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Exportar CSV
            </button>
            <button wire:click="openCreate"
                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                + Nueva inscripción
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search"
            type="text" placeholder="Buscar por nombre, email o teléfono..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

        <select wire:model.live="filterEvent"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Todos los eventos</option>
            @foreach ($events as $event)
                <option value="{{ $event->id }}">{{ $event->title }}</option>
            @endforeach
        </select>
    </div>

    {{-- Stats bar --}}
    @if($registrations->total() > 0)
        <div class="flex items-center gap-6 mb-4 text-sm text-gray-500">
            <span>
                <span class="font-semibold text-gray-700">{{ $registrations->total() }}</span>
                {{ $registrations->total() === 1 ? 'inscripción' : 'inscripciones' }}
            </span>
            <span>
                <span class="font-semibold text-gray-700">{{ $registrations->sum('attendees') }}</span>
                asistentes en esta página
            </span>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Evento</th>
                    <th class="px-5 py-3 text-left">Nombre</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Teléfono</th>
                    <th class="px-5 py-3 text-center">Asistentes</th>
                    <th class="px-5 py-3 text-left">Fecha</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($registrations as $reg)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            @if ($reg->event)
                                <span class="font-medium text-gray-800 text-xs">{{ $reg->event->title }}</span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $reg->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $reg->email }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $reg->phone ?: '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">
                                {{ $reg->attendees }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">
                            {{ $reg->created_at->format('d/m/Y') }}<br>
                            {{ $reg->created_at->format('H:i') }}
                        </td>
                        <td class="px-5 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $reg->id }})"
                                class="text-indigo-500 hover:text-indigo-700 text-xs font-medium">Editar</button>
                            <button wire:click="delete({{ $reg->id }})"
                                wire:confirm="¿Eliminar esta inscripción?"
                                class="text-red-400 hover:text-red-600 text-xs font-medium">Eliminar</button>
                        </td>
                    </tr>
                    @if ($reg->notes)
                        <tr class="bg-amber-50">
                            <td colspan="7" class="px-5 py-2 text-xs text-amber-700 italic">
                                Nota: {{ $reg->notes }}
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No se encontraron inscripciones.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $registrations->links() }}</div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">
                        {{ $isEditing ? 'Editar inscripción' : 'Nueva inscripción' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>

                <div class="px-6 py-5 space-y-4">

                    {{-- Evento --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Evento *</label>
                        <select wire:model="event_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">— Seleccionar evento —</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->title }}</option>
                            @endforeach
                        </select>
                        @error('event_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nombre *</label>
                        <input wire:model="name" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Email *</label>
                        <input wire:model="email" type="email"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Teléfono + Asistentes --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Teléfono</label>
                            <input wire:model="phone" type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Asistentes *</label>
                            <input wire:model="attendees" type="number" min="1" max="100"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('attendees') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Notas --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                        <textarea wire:model="notes" rows="3" placeholder="Necesidades especiales, comentarios..."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancelar</button>
                    <button wire:click="save"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                        {{ $isEditing ? 'Guardar cambios' : 'Crear inscripción' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
