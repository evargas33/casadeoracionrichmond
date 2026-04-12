<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Series</h1>
        <button wire:click="openCreate"
            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
            + New Series
        </button>
    </div>

    <div class="flex gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search"
            type="text" placeholder="Search series..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

        <select wire:model.live="filterActive"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All statuses</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Title</th>
                    <th class="px-5 py-3 text-center">Sermons</th>
                    <th class="px-5 py-3 text-center">Order</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($series as $serie)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $serie->title }}</p>
                            @if ($serie->description)
                                <p class="text-gray-400 text-xs mt-0.5 truncate max-w-xs">{{ $serie->description }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center text-gray-500">{{ $serie->sermons_count }}</td>
                        <td class="px-5 py-3 text-center text-gray-500">{{ $serie->order }}</td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="toggleActive({{ $serie->id }})"
                                class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                    {{ $serie->active
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $serie->active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $serie->id }})"
                                class="text-indigo-500 hover:text-indigo-700 text-xs font-medium">Edit</button>
                            <button wire:click="delete({{ $serie->id }})"
                                wire:confirm="Delete this series? Sermons will not be deleted."
                                class="text-red-400 hover:text-red-600 text-xs font-medium">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No series found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $series->links() }}
    </div>

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">
                        {{ $isEditing ? 'Edit Series' : 'New Series' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>

                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Title *</label>
                        <input wire:model="title" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
                    </div>

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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Order</label>
                            <input wire:model="order" type="number" min="0"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                        <div class="flex items-center gap-2 pt-5">
                            <input wire:model="active" type="checkbox" id="active"
                                class="rounded border-gray-300 text-indigo-600">
                            <label for="active" class="text-sm text-gray-600">Active</label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                    <button wire:click="save"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                        {{ $isEditing ? 'Save changes' : 'Create series' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
