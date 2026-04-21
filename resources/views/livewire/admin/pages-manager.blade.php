<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Pages</h1>
        <button wire:click="openCreate"
            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
            + New Page
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by title or slug..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

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
                    <th class="px-5 py-3 text-left">Slug</th>
                    <th class="px-5 py-3 text-left">Template</th>
                    <th class="px-5 py-3 text-center">In menu</th>
                    <th class="px-5 py-3 text-center">Order</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pages as $page)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $page->title }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">{{ $page->author->name }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <code class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                /{{ $page->slug }}
                            </code>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $page->template }}</td>
                        <td class="px-5 py-3 text-center">
                            @if ($page->in_menu)
                                <span class="text-green-500 text-xs font-medium">Yes</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center text-gray-500">{{ $page->order }}</td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="togglePublished({{ $page->id }})"
                                class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                    {{ $page->published
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $page->published ? 'Published' : 'Draft' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $page->id }})"
                                class="text-indigo-500 hover:text-indigo-700 text-xs font-medium">Edit</button>
                            <button wire:click="delete({{ $page->id }})" wire:confirm="Delete this page?"
                                class="text-red-400 hover:text-red-600 text-xs font-medium">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No pages found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pages->links() }}</div>

    {{-- Modal --}}
    @if ($showModal)

        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">
                        {{ $isEditing ? 'Edit Page' : 'New Page' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>

                <div class="px-6 py-5 space-y-4">

                    {{-- Title --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Title *</label>
                        <input wire:model.blur="title" type="text"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Slug *</label>
                        <div
                            class="flex items-center border border-gray-200 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-indigo-300">
                            <span class="px-3 py-2 bg-gray-50 text-gray-400 text-sm border-r border-gray-200">/</span>
                            <input wire:model="slug" type="text" class="flex-1 px-3 py-2 text-sm focus:outline-none">
                        </div>
                        @error('slug')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Template + Category --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Template *</label>
                            <select wire:model="template"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="default">Default</option>
                                <option value="about">About</option>
                                <option value="contact">Contact</option>
                                <option value="visit-us">Visit us</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                            <select wire:model="category_id"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">— None —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Content *</label>
                        <div wire:ignore>
                            <textarea id="pageContent" rows="6" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                            </textarea>
                        </div>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SEO --}}
                    <div class="border border-gray-100 rounded-lg p-4 space-y-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">SEO</p>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Meta title <span
                                    class="text-gray-400 font-normal">(max 70)</span></label>
                            <input wire:model="meta_title" type="text" maxlength="70"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Meta description <span
                                    class="text-gray-400 font-normal">(max 160)</span></label>
                            <textarea wire:model="meta_description" rows="2" maxlength="160"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">OG Image</label>
                            <div class="space-y-1.5">
                                @if($og_image)
                                    <img src="{{ $og_image }}" alt="Preview"
                                        class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                @endif
                                <button type="button"
                                    wire:click="$dispatch('openMediaPicker', { field: 'og_image' })"
                                    class="w-full flex items-center justify-center gap-2 border border-dashed border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-500 hover:border-indigo-400 hover:text-indigo-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                    {{ $og_image ? 'Cambiar imagen' : 'Seleccionar de la biblioteca' }}
                                </button>
                                @if($og_image)
                                    <button type="button" wire:click="$set('og_image', '')"
                                        class="text-xs text-red-400 hover:text-red-600">Quitar imagen</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Menu settings --}}
                    <div class="grid grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Menu order</label>
                            <input wire:model="order" type="number" min="0"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                        <div class="flex flex-col gap-2 pb-2">
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input wire:model="published" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600">
                                Published
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input wire:model="in_menu" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600">
                                Show in menu
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                    <button wire:click="save"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                        {{ $isEditing ? 'Save changes' : 'Create page' }}
                    </button>
                </div>
            </div>
        </div>
    @endif


    <script>
        window.addEventListener('open-editor', function() {
            setTimeout(function() {
                if (tinymce.get('pageContent')) {
                    tinymce.remove('#pageContent');
                }
                tinymce.init({
                    selector: '#pageContent',
                    base_url: '/build/tinymce',
                    suffix: '.min',
                    license_key: 'gpl',
                    skin_url: '/build/tinymce/skins/ui/oxide',
                    content_css: '/build/tinymce/skins/content/default/content.min.css',
                    height: 380,
                    menubar: false,
                    plugins: 'link lists image media table code fullscreen wordcount',
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | table | code fullscreen',
                    promotion: false,
                    branding: false,
                    setup(editor) {
                        editor.on('Change KeyUp', function() {
                            @this.set('content', editor.getContent());
                            document.getElementById('pageContent').dispatchEvent(new Event(
                                'input'));
                        });

                        editor.on('init', function() {
                            if (@this.content) {
                                editor.setContent(@this.content) || '';
                            }
                        });
                    },
                });
            }, 100);
        });

        window.addEventListener('close-editor', function() {
            if (tinymce.get('pageContent')) {
                tinymce.remove('#pageContent');
            }
        });
    </script>
</div>
