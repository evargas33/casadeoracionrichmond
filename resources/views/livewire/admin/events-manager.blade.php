
<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Events</h1>
        <button wire:click="openCreate"
            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
            + New Event
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search"
            type="text" placeholder="Search by title or location..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

        <select wire:model.live="filterCategory"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterPublished"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All statuses</option>
            <option value="1">Published</option>
            <option value="0">Draft</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Title</th>
                    <th class="px-5 py-3 text-left">Category</th>
                    <th class="px-5 py-3 text-left">Start date</th>
                    <th class="px-5 py-3 text-left">Location</th>
                    <th class="px-5 py-3 text-center">Featured</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($events as $event)
                    <tr wire:key="event-{{ $event->id }}" class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $event->title }}</p>
                            @if ($event->short_description)
                                <p class="text-gray-400 text-xs mt-0.5 truncate max-w-xs">{{ $event->short_description }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if ($event->category)
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                    style="background: {{ $event->category->color }}20; color: {{ $event->category->color }}">
                                    {{ $event->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">
                            {{ $event->start_date->format('M d, Y') }}<br>
                            {{ $event->start_date->format('g:i A') }}
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $event->location ?? '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="toggleFeatured({{ $event->id }})"
                                class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                    {{ $event->featured
                                        ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                                        : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                {{ $event->featured ? 'Featured' : 'No' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="togglePublished({{ $event->id }})"
                                class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                    {{ $event->published
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $event->published ? 'Published' : 'Draft' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-right space-x-2">
                            <button wire:click="openRegistrations({{ $event->id }})"
                                class="text-emerald-500 hover:text-emerald-700 text-xs font-medium">
                                Inscritos ({{ $event->registrations_count }})
                            </button>
                            @if(auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']))
                            <button wire:click="openEdit({{ $event->id }})"
                                class="text-indigo-500 hover:text-indigo-700 text-xs font-medium">Edit</button>
                            @endif
                            @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                            <button wire:click="delete({{ $event->id }})"
                                wire:confirm="¿Eliminar este evento?"
                                class="text-red-400 hover:text-red-600 text-xs font-medium">Delete</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No events found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $events->links() }}</div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">
                        {{ $isEditing ? 'Edit Event' : 'New Event' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>

                <div class="px-6 py-5 space-y-4">

                    {{-- Title --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Title *</label>
                        <input wire:model="title" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                        <select wire:model="category_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">— No category —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Start / End date --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Start date *</label>
                            <input wire:model="start_date" type="datetime-local"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">End date</label>
                            <input wire:model="end_date" type="datetime-local"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Location</label>
                            <input wire:model="location" type="text" placeholder="Venue name"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Capacity</label>
                            <input wire:model="capacity" type="number" min="1" placeholder="Leave blank = unlimited"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Imagen</label>
                        <div class="flex gap-2 items-start">
                            @if($image)
                                <img src="{{ $image }}" alt="Preview"
                                    class="w-16 h-16 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                            @endif
                            <div class="flex-1 space-y-1.5">
                                <button type="button"
                                    wire:click="$dispatch('openMediaPicker', { field: 'image' })"
                                    class="w-full flex items-center justify-center gap-2 border border-dashed border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-500 hover:border-indigo-400 hover:text-indigo-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                    {{ $image ? 'Cambiar imagen' : 'Seleccionar de la biblioteca' }}
                                </button>
                                @if($image)
                                    <button type="button" wire:click="$set('image', '')"
                                        class="text-xs text-red-400 hover:text-red-600">Quitar imagen</button>
                                @endif
                            </div>
                        </div>
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Short description --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Short description</label>
                        <input wire:model="short_description" type="text" placeholder="For event cards (~160 chars)"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description *</label>
                        <textarea wire:model="description" rows="4"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Checkboxes --}}
                    <div class="flex flex-wrap gap-5 pt-1">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input wire:model="published" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600">
                            Published
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input wire:model="featured" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600">
                            Featured
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input wire:model="all_day" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600">
                            All day
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input wire:model="requires_registration" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600">
                            Requires registration
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                    <button wire:click="save"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                        {{ $isEditing ? 'Save changes' : 'Create event' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Registrations modal --}}
    @if($showRegistrations)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                    <div>
                        <h2 class="font-semibold text-gray-800">Inscritos</h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $registrationsEvent?->title }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-400">
                            {{ $registrations->count() }} {{ $registrations->count() === 1 ? 'persona' : 'personas' }}
                            · {{ $registrations->sum('attendees') }} asistentes totales
                        </span>
                        <button wire:click="closeRegistrations" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                    </div>
                </div>

                <div class="overflow-y-auto flex-1">
                    @if($registrations->isEmpty())
                        <div class="py-16 text-center text-gray-400 text-sm">
                            No hay inscritos aún.
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                                    <th class="px-5 py-3 text-left">Nombre</th>
                                    <th class="px-5 py-3 text-left">Correo</th>
                                    <th class="px-5 py-3 text-left">Teléfono</th>
                                    <th class="px-5 py-3 text-center">Asistentes</th>
                                    <th class="px-5 py-3 text-left">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($registrations as $reg)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-3 font-medium text-gray-800">{{ $reg->name }}</td>
                                        <td class="px-5 py-3 text-gray-500">{{ $reg->email }}</td>
                                        <td class="px-5 py-3 text-gray-500">{{ $reg->phone ?: '—' }}</td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">
                                                {{ $reg->attendees }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @if($reg->notes)
                                        <tr class="bg-amber-50">
                                            <td colspan="5" class="px-5 py-2 text-xs text-amber-700 italic">
                                                Nota: {{ $reg->notes }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
