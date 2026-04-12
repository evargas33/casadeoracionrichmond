<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Sermons</h1>
        <button wire:click="openCreate"
            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
            + New Sermon
        </button>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search"
            type="text" placeholder="Search by title or speaker..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

        <select wire:model.live="filterSeries"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All series</option>
            @foreach ($allSeries as $s)
                <option value="{{ $s->id }}">{{ $s->title }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterPublished"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">All statuses</option>
            <option value="1">Published</option>
            <option value="0">Draft</option>
        </select>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Title</th>
                    <th class="px-5 py-3 text-left">Speaker</th>
                    <th class="px-5 py-3 text-left">Series</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($sermons as $sermon)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $sermon->title }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $sermon->speaker }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $sermon->series?->title ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $sermon->date->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="togglePublished({{ $sermon->id }})"
                                class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                    {{ $sermon->published
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $sermon->published ? 'Published' : 'Draft' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $sermon->id }})"
                                class="text-indigo-500 hover:text-indigo-700 text-xs font-medium">Edit</button>
                            <button wire:click="delete({{ $sermon->id }})"
                                wire:confirm="Delete this sermon?"
                                class="text-red-400 hover:text-red-600 text-xs font-medium">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No sermons found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sermons->links() }}
    </div>

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">
                        {{ $isEditing ? 'Edit Sermon' : 'New Sermon' }}
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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Speaker *</label>
                            <input wire:model="speaker" type="text"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('speaker') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date *</label>
                            <input wire:model="date" type="date"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Series</label>
                            <select wire:model="series_id"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">— No series —</option>
                                @foreach ($allSeries as $s)
                                    <option value="{{ $s->id }}">{{ $s->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Bible passage</label>
                            <input wire:model="bible_passage" type="text" placeholder="e.g. John 3:16"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                SoundCloud URL
                                <span class="text-gray-400 font-normal ml-1">— audio</span>
                            </label>
                            <input wire:model="audio_url" type="url" placeholder="https://soundcloud.com/..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('audio_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                YouTube URL
                                <span class="text-gray-400 font-normal ml-1">— video</span>
                            </label>
                            <input wire:model="video_url" type="url" placeholder="https://youtube.com/watch?v=..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            @error('video_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="w-1/2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Duration (minutes)</label>
                        <input wire:model="duration_minutes" type="number" min="1" max="300"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input wire:model="published" type="checkbox" id="published"
                            class="rounded border-gray-300 text-indigo-600">
                        <label for="published" class="text-sm text-gray-600">Publish immediately</label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                    <button wire:click="save"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                        {{ $isEditing ? 'Save changes' : 'Create sermon' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
