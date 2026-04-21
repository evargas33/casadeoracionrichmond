<div>

    {{-- ═══════════════════════════════════════════════════
         FLASH
    ════════════════════════════════════════════════════ --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         LIST VIEW
    ════════════════════════════════════════════════════ --}}
    @if ($currentView === 'list')

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Planificación de Servicios</h1>
            @if ($isAdmin)
                <button wire:click="openCreate"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                    + Nuevo Servicio
                </button>
            @endif
        </div>

        {{-- Filters --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-5">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar por título o tema..."
                class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

            <select wire:model.live="filterStatus"
                class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">Todos los estados</option>
                <option value="publicado">Publicado</option>
                <option value="borrador">Borrador</option>
            </select>

            <select wire:model.live="filterType"
                class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">Todos los tipos</option>
                <option value="domingo">Domingo</option>
                <option value="sabado">Sábado</option>
                <option value="viernes">Viernes</option>
                <option value="especial">Especial</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Fecha / Título</th>
                        <th class="px-5 py-3 text-left">Tipo</th>
                        <th class="px-5 py-3 text-center">Completitud</th>
                        <th class="px-5 py-3 text-center">Estado</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($plans as $plan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-800">{{ $plan->title }}</p>
                                <p class="text-gray-400 text-xs mt-0.5">
                                    {{ $plan->date->translatedFormat('l, d \d\e F \d\e Y') }}
                                </p>
                                @if ($plan->sermon_topic)
                                    <p class="text-gray-500 text-xs italic mt-0.5 truncate max-w-xs">
                                        "{{ $plan->sermon_topic }}"
                                    </p>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ $plan->service_type_label }}
                                </span>
                            </td>
                            {{-- Completion indicators --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    @php
                                        $checks = [
                                            ['label' => 'Pred.', 'done' => (bool) $plan->sermon_topic],
                                            ['label' => 'Alab.', 'done' => $plan->songs_count > 0 || $plan->worship_uniform_color],
                                            ['label' => 'Uj.',   'done' => $plan->ushers_count > 0],
                                            ['label' => 'Tec.',  'done' => $plan->technicians_count > 0],
                                        ];
                                    @endphp
                                    @foreach ($checks as $c)
                                        <span class="flex items-center gap-1 text-xs {{ $c['done'] ? 'text-green-600' : 'text-gray-300' }}">
                                            @if ($c['done'])
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <circle cx="12" cy="12" r="9"/>
                                                </svg>
                                            @endif
                                            {{ $c['label'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($isAdmin)
                                    <button wire:click="toggleStatus({{ $plan->id }})"
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                            {{ $plan->status === 'publicado'
                                                ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                                : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        {{ $plan->status === 'publicado' ? 'Publicado' : 'Borrador' }}
                                    </button>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $plan->status === 'publicado' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $plan->status === 'publicado' ? 'Publicado' : 'Borrador' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right space-x-2">
                                <button wire:click="openEdit({{ $plan->id }})"
                                    class="text-indigo-500 hover:text-indigo-700 text-xs font-medium">
                                    Editar
                                </button>
                                @if ($isAdmin)
                                    <button wire:click="delete({{ $plan->id }})"
                                        wire:confirm="¿Eliminar este plan de servicio? Esta acción no se puede deshacer."
                                        class="text-red-400 hover:text-red-600 text-xs font-medium">
                                        Eliminar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-400 text-sm">
                                No hay servicios registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $plans->links() }}</div>

    @endif {{-- end list view --}}


    {{-- ═══════════════════════════════════════════════════
         DETAIL / FORM VIEW
    ════════════════════════════════════════════════════ --}}
    @if ($currentView === 'detail')

        {{-- Tab success flash --}}
        @if (session('tab_success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('tab_success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6">
            <button wire:click="backToList"
                class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Volver
            </button>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">
                    {{ $isEditing ? $plan_title : 'Nuevo Servicio' }}
                </h1>
                @if ($isEditing && $plan_date)
                    <p class="text-sm text-gray-400 mt-0.5">
                        {{ \Carbon\Carbon::parse($plan_date)->translatedFormat('l, d \d\e F \d\e Y') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-0 -mb-px overflow-x-auto">
                @php
                    $tabs = [
                        ['key' => 'general',     'label' => 'General',     'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['key' => 'predicacion', 'label' => 'Predicación', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['key' => 'alabanza',    'label' => 'Alabanza',    'icon' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3'],
                        ['key' => 'ujieres',     'label' => 'Ujieres',     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        ['key' => 'tecnicos',    'label' => 'Técnicos',    'icon' => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18'],
                    ];
                @endphp
                @foreach ($tabs as $tab)
                    <button wire:click="$set('activeTab', '{{ $tab['key'] }}')"
                        @if (!$isEditing && $tab['key'] !== 'general') disabled @endif
                        class="flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors
                            {{ $activeTab === $tab['key']
                                ? 'border-indigo-600 text-indigo-600'
                                : 'border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-300' }}
                            {{ !$isEditing && $tab['key'] !== 'general' ? 'opacity-40 cursor-not-allowed' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/>
                        </svg>
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- ─── TAB: GENERAL ─── --}}
        @if ($activeTab === 'general')
            <div class="bg-white border border-gray-200 rounded-xl p-6 max-w-2xl space-y-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Fecha del servicio *</label>
                    <input wire:model="plan_date" type="date"
                        @if (!$canEditGeneral) disabled @endif
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                    @error('plan_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Título del servicio *</label>
                    <input wire:model="plan_title" type="text" placeholder="Ej: Servicio Dominical"
                        @if (!$canEditGeneral) disabled @endif
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                    @error('plan_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tipo de servicio *</label>
                        <select wire:model="plan_service_type"
                            @if (!$canEditGeneral) disabled @endif
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                            <option value="domingo">Domingo</option>
                            <option value="sabado">Sábado</option>
                            <option value="viernes">Viernes</option>
                            <option value="especial">Especial</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Estado *</label>
                        <select wire:model="plan_status"
                            @if (!$canEditGeneral) disabled @endif
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                            <option value="borrador">Borrador (solo admins lo ven)</option>
                            <option value="publicado">Publicado (visible para servidores)</option>
                        </select>
                    </div>
                </div>

                @if ($canEditGeneral)
                    <div class="pt-2">
                        <button wire:click="saveGeneral"
                            class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                            {{ $isEditing ? 'Guardar cambios' : 'Crear servicio' }}
                        </button>
                    </div>
                @endif
            </div>
        @endif

        {{-- ─── TAB: PREDICACIÓN ─── --}}
        @if ($activeTab === 'predicacion')
            <div class="bg-white border border-gray-200 rounded-xl p-6 max-w-2xl space-y-5">

                @if (!$isEditing)
                    <p class="text-sm text-gray-400">Primero guarda la información general para habilitar esta sección.</p>
                @else
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tema a predicar</label>
                        <input wire:model="sermon_topic" type="text"
                            placeholder="Ej: La fe que mueve montañas"
                            @if (!$canEditPredicacion) disabled @endif
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                        @error('sermon_topic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Pasaje bíblico principal</label>
                        <input wire:model="bible_passage" type="text"
                            placeholder="Ej: Mateo 17:20"
                            @if (!$canEditPredicacion) disabled @endif
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                        @error('bible_passage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sermon notes file --}}
                    <div class="p-4 border border-gray-100 rounded-lg bg-gray-50 space-y-3">
                        <div>
                            <p class="text-xs font-semibold text-gray-700">Notas del sermón</p>
                            <p class="text-xs text-gray-400">PDF o Word con notas de preparación del pastor.</p>
                        </div>
                        @if ($sermon_notes_path)
                            <div class="flex items-center justify-between bg-white border border-gray-200 rounded-lg px-3 py-2">
                                <a href="{{ asset('storage/' . $sermon_notes_path) }}" target="_blank"
                                    class="text-xs text-indigo-600 hover:underline flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Ver archivo actual
                                </a>
                                @if ($canEditPredicacion)
                                    <button wire:click="removeSermonNotesFile"
                                        class="text-xs text-red-400 hover:text-red-600">Quitar</button>
                                @endif
                            </div>
                        @endif
                        @if ($canEditPredicacion)
                            <input wire:model="sermon_notes_file" type="file" accept=".pdf,.doc,.docx"
                                class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('sermon_notes_file') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    {{-- Bible citations file --}}
                    <div class="p-4 border border-amber-100 rounded-lg bg-amber-50 space-y-3">
                        <div>
                            <p class="text-xs font-semibold text-amber-800">Lista de citas bíblicas
                                <span class="ml-1.5 font-normal text-amber-600">(para operador de ProPresenter)</span>
                            </p>
                            <p class="text-xs text-amber-600">
                                El encargado de proyección usará este archivo para pre-cargar los versículos.
                            </p>
                        </div>
                        @if ($bible_citations_path)
                            <div class="flex items-center justify-between bg-white border border-amber-200 rounded-lg px-3 py-2">
                                <a href="{{ asset('storage/' . $bible_citations_path) }}" target="_blank"
                                    class="text-xs text-amber-700 hover:underline flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Ver lista de citas
                                </a>
                                @if ($canEditPredicacion)
                                    <button wire:click="removeBibleCitationsFile"
                                        class="text-xs text-red-400 hover:text-red-600">Quitar</button>
                                @endif
                            </div>
                        @endif
                        @if ($canEditPredicacion)
                            <input wire:model="bible_citations_file" type="file" accept=".pdf,.doc,.docx"
                                class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200">
                            @error('bible_citations_file') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    @if ($canEditPredicacion)
                        <div class="pt-2">
                            <button wire:click="savePredicacion"
                                class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                                Guardar Predicación
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        @endif

        {{-- ─── TAB: ALABANZA ─── --}}
        @if ($activeTab === 'alabanza')
            <div class="max-w-3xl space-y-5">

                @if (!$isEditing)
                    <p class="text-sm text-gray-400">Primero guarda la información general para habilitar esta sección.</p>
                @else
                    {{-- Uniform --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
                        <h3 class="font-medium text-gray-800 text-sm">Uniforme del equipo de alabanza</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Color / descripción</label>
                                <input wire:model="worship_uniform_color" type="text"
                                    placeholder="Ej: Blanco con accent dorado"
                                    @if (!$canEditAlabanza) disabled @endif
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                                @error('worship_uniform_color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Notas adicionales</label>
                                <input wire:model="worship_uniform_notes" type="text"
                                    placeholder="Ej: Zapatos negros, sin accesorios"
                                    @if (!$canEditAlabanza) disabled @endif
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                                @error('worship_uniform_notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if ($canEditAlabanza)
                            <button wire:click="saveAlabanza"
                                class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                                Guardar uniforme
                            </button>
                        @endif
                    </div>

                    {{-- Songs list --}}
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <h3 class="font-medium text-gray-800 text-sm">Cantos del servicio ({{ count($songs) }})</h3>
                            @if ($canEditAlabanza && !$showSongForm)
                                <button wire:click="openSongForm"
                                    class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs rounded-lg hover:bg-indigo-100 transition">
                                    + Agregar canto
                                </button>
                            @endif
                        </div>

                        {{-- Inline song form --}}
                        @if ($showSongForm && $canEditAlabanza)
                            <div class="px-5 py-4 bg-indigo-50 border-b border-indigo-100 space-y-4">
                                <h4 class="text-xs font-semibold text-indigo-800 uppercase tracking-wide">
                                    {{ $editing_song_id ? 'Editar canto' : 'Nuevo canto' }}
                                </h4>

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Título *</label>
                                        <input wire:model="song_title" type="text" placeholder="Nombre del canto"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                        @error('song_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Orden</label>
                                        <input wire:model="song_order" type="number" min="1" max="255"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Artista / Autor</label>
                                        <input wire:model="song_artist" type="text" placeholder="Ej: Hillsong"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Tono (Key)</label>
                                        <input wire:model="song_key" type="text" placeholder="Ej: G, D, Bb"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                                    <input wire:model="song_notes" type="text" placeholder="Ej: Instrumental al final"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- OnSong file --}}
                                    <div class="space-y-1.5">
                                        <p class="text-xs font-medium text-gray-600">Archivo OnSong</p>
                                        @if ($song_onsong_path && !$song_onsong_file)
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-green-600">✓ Archivo cargado</span>
                                                @if ($editing_song_id)
                                                    <button wire:click="removeSongOnsong({{ $editing_song_id }})"
                                                        class="text-xs text-red-400 hover:text-red-600">Quitar</button>
                                                @endif
                                            </div>
                                        @endif
                                        <input wire:model="song_onsong_file" type="file" accept=".onsong,.txt"
                                            class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700">
                                        @error('song_onsong_file') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- PDF file --}}
                                    <div class="space-y-1.5">
                                        <p class="text-xs font-medium text-gray-600">Archivo PDF</p>
                                        @if ($song_pdf_path && !$song_pdf_file)
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-green-600">✓ PDF cargado</span>
                                                @if ($editing_song_id)
                                                    <button wire:click="removeSongPdf({{ $editing_song_id }})"
                                                        class="text-xs text-red-400 hover:text-red-600">Quitar</button>
                                                @endif
                                            </div>
                                        @endif
                                        <input wire:model="song_pdf_file" type="file" accept=".pdf"
                                            class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700">
                                        @error('song_pdf_file') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button wire:click="saveSong"
                                        class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                                        {{ $editing_song_id ? 'Actualizar' : 'Agregar' }}
                                    </button>
                                    <button wire:click="$set('showSongForm', false)"
                                        class="px-4 py-1.5 text-sm text-gray-500 hover:text-gray-700">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Songs table --}}
                        @if (count($songs) > 0)
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wide">
                                    <tr>
                                        <th class="px-5 py-2.5 text-left w-8">#</th>
                                        <th class="px-5 py-2.5 text-left">Canto</th>
                                        <th class="px-5 py-2.5 text-left">Tono</th>
                                        <th class="px-5 py-2.5 text-center">Archivos</th>
                                        @if ($canEditAlabanza)
                                            <th class="px-5 py-2.5 text-right">Acciones</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($songs as $song)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-5 py-3 text-gray-400 text-xs">{{ $song['order'] }}</td>
                                            <td class="px-5 py-3">
                                                <p class="font-medium text-gray-800">{{ $song['title'] }}</p>
                                                @if ($song['artist'])
                                                    <p class="text-gray-400 text-xs">{{ $song['artist'] }}</p>
                                                @endif
                                                @if ($song['notes'])
                                                    <p class="text-gray-400 text-xs italic">{{ $song['notes'] }}</p>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3 text-gray-500 text-sm">{{ $song['song_key'] ?? '—' }}</td>
                                            <td class="px-5 py-3 text-center space-x-2">
                                                @if ($song['onsong_path'])
                                                    <a href="{{ asset('storage/' . $song['onsong_path']) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:underline">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        OnSong
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-300">—</span>
                                                @endif
                                                @if ($song['pdf_path'])
                                                    <a href="{{ asset('storage/' . $song['pdf_path']) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-1 text-xs text-red-500 hover:underline">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        PDF
                                                    </a>
                                                @endif
                                            </td>
                                            @if ($canEditAlabanza)
                                                <td class="px-5 py-3 text-right space-x-2">
                                                    <button wire:click="openSongForm({{ $song['id'] }})"
                                                        class="text-indigo-500 hover:text-indigo-700 text-xs">Editar</button>
                                                    <button wire:click="deleteSong({{ $song['id'] }})"
                                                        wire:confirm="¿Eliminar este canto?"
                                                        class="text-red-400 hover:text-red-600 text-xs">Eliminar</button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="py-10 text-center text-gray-400 text-sm">
                                No hay cantos agregados aún.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- ─── TAB: UJIERES ─── --}}
        @if ($activeTab === 'ujieres')
            <div class="max-w-3xl space-y-5">

                @if (!$isEditing)
                    <p class="text-sm text-gray-400">Primero guarda la información general para habilitar esta sección.</p>
                @else
                    {{-- Uniform --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
                        <h3 class="font-medium text-gray-800 text-sm">Uniforme de ujieres</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Color / descripción</label>
                                <input wire:model="usher_uniform_color" type="text"
                                    placeholder="Ej: Negro formal"
                                    @if (!$canEditUjieres) disabled @endif
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Notas adicionales</label>
                                <input wire:model="usher_uniform_notes" type="text"
                                    placeholder="Ej: Gafete visible, corbata oscura"
                                    @if (!$canEditUjieres) disabled @endif
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 disabled:bg-gray-50 disabled:text-gray-400">
                            </div>
                        </div>
                        @if ($canEditUjieres)
                            <button wire:click="saveUjieres"
                                class="px-5 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                                Guardar uniforme
                            </button>
                        @endif
                    </div>

                    {{-- Ushers list --}}
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <h3 class="font-medium text-gray-800 text-sm">Ujieres ({{ count($ushers) }})</h3>
                            @if ($canEditUjieres && !$showUsherForm)
                                <button wire:click="openUsherForm"
                                    class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs rounded-lg hover:bg-indigo-100 transition">
                                    + Agregar ujier
                                </button>
                            @endif
                        </div>

                        {{-- Inline usher form --}}
                        @if ($showUsherForm && $canEditUjieres)
                            <div class="px-5 py-4 bg-indigo-50 border-b border-indigo-100 space-y-4">
                                <h4 class="text-xs font-semibold text-indigo-800 uppercase tracking-wide">
                                    {{ $editing_usher_id ? 'Editar ujier' : 'Agregar ujier' }}
                                </h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Nombre *</label>
                                        <input wire:model="usher_name" type="text" placeholder="Nombre completo"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                        @error('usher_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Asignación *</label>
                                        <select wire:model="usher_assignment"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                            <option value="entrada">Entrada</option>
                                            <option value="ofrendas">Ofrendas</option>
                                            <option value="general">General</option>
                                            <option value="apoyo">Apoyo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Usuario registrado (opcional)</label>
                                        <select wire:model.live="usher_user_id"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                            <option value="">— Sin cuenta registrada —</option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                                        <input wire:model="usher_notes" type="text" placeholder="Ej: Llegar 30 min antes"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <button wire:click="saveUsher"
                                        class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                                        {{ $editing_usher_id ? 'Actualizar' : 'Agregar' }}
                                    </button>
                                    <button wire:click="$set('showUsherForm', false)"
                                        class="px-4 py-1.5 text-sm text-gray-500 hover:text-gray-700">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Ushers table --}}
                        @if (count($ushers) > 0)
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wide">
                                    <tr>
                                        <th class="px-5 py-2.5 text-left">Nombre</th>
                                        <th class="px-5 py-2.5 text-left">Asignación</th>
                                        <th class="px-5 py-2.5 text-left">Notas</th>
                                        @if ($canEditUjieres)
                                            <th class="px-5 py-2.5 text-right">Acciones</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($ushers as $usher)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-5 py-3 font-medium text-gray-800">{{ $usher['name'] }}</td>
                                            <td class="px-5 py-3">
                                                @php
                                                    $aColors = ['entrada' => 'blue', 'ofrendas' => 'amber', 'general' => 'green', 'apoyo' => 'gray'];
                                                    $c = $aColors[$usher['assignment']] ?? 'gray';
                                                    $labels = ['entrada' => 'Entrada', 'ofrendas' => 'Ofrendas', 'general' => 'General', 'apoyo' => 'Apoyo'];
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                    bg-{{ $c }}-100 text-{{ $c }}-700">
                                                    {{ $labels[$usher['assignment']] ?? $usher['assignment'] }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-gray-400 text-xs">{{ $usher['notes'] ?? '—' }}</td>
                                            @if ($canEditUjieres)
                                                <td class="px-5 py-3 text-right space-x-2">
                                                    <button wire:click="openUsherForm({{ $usher['id'] }})"
                                                        class="text-indigo-500 hover:text-indigo-700 text-xs">Editar</button>
                                                    <button wire:click="deleteUsher({{ $usher['id'] }})"
                                                        wire:confirm="¿Eliminar este ujier?"
                                                        class="text-red-400 hover:text-red-600 text-xs">Eliminar</button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="py-10 text-center text-gray-400 text-sm">No hay ujieres agregados aún.</div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- ─── TAB: TÉCNICOS ─── --}}
        @if ($activeTab === 'tecnicos')
            <div class="max-w-3xl">

                @if (!$isEditing)
                    <p class="text-sm text-gray-400">Primero guarda la información general para habilitar esta sección.</p>
                @else
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <h3 class="font-medium text-gray-800 text-sm">Equipo técnico ({{ count($technicians) }})</h3>
                            @if ($canEditTecnicos && !$showTechForm)
                                <button wire:click="openTechForm"
                                    class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs rounded-lg hover:bg-indigo-100 transition">
                                    + Agregar técnico
                                </button>
                            @endif
                        </div>

                        {{-- Inline tech form --}}
                        @if ($showTechForm && $canEditTecnicos)
                            <div class="px-5 py-4 bg-indigo-50 border-b border-indigo-100 space-y-4">
                                <h4 class="text-xs font-semibold text-indigo-800 uppercase tracking-wide">
                                    {{ $editing_tech_id ? 'Editar técnico' : 'Agregar técnico' }}
                                </h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Nombre *</label>
                                        <input wire:model="tech_name" type="text" placeholder="Nombre completo"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                        @error('tech_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Posición *</label>
                                        <select wire:model="tech_position"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                            <option value="mixer">Mixer / Sonido</option>
                                            <option value="proyeccion">Proyección (ProPresenter)</option>
                                            <option value="streaming">Streaming</option>
                                            <option value="apoyo">Apoyo técnico</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Usuario registrado (opcional)</label>
                                        <select wire:model.live="tech_user_id"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                            <option value="">— Sin cuenta registrada —</option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                                        <input wire:model="tech_notes" type="text" placeholder="Ej: Llegar 45 min antes"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <button wire:click="saveTech"
                                        class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                                        {{ $editing_tech_id ? 'Actualizar' : 'Agregar' }}
                                    </button>
                                    <button wire:click="$set('showTechForm', false)"
                                        class="px-4 py-1.5 text-sm text-gray-500 hover:text-gray-700">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Technicians table --}}
                        @if (count($technicians) > 0)
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wide">
                                    <tr>
                                        <th class="px-5 py-2.5 text-left">Nombre</th>
                                        <th class="px-5 py-2.5 text-left">Posición</th>
                                        <th class="px-5 py-2.5 text-left">Notas</th>
                                        @if ($canEditTecnicos)
                                            <th class="px-5 py-2.5 text-right">Acciones</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($technicians as $tech)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-5 py-3 font-medium text-gray-800">{{ $tech['name'] }}</td>
                                            <td class="px-5 py-3">
                                                @php
                                                    $posLabels = [
                                                        'mixer'      => ['label' => 'Mixer / Sonido',       'color' => 'purple'],
                                                        'proyeccion' => ['label' => 'Proyección',           'color' => 'blue'],
                                                        'streaming'  => ['label' => 'Streaming',            'color' => 'red'],
                                                        'apoyo'      => ['label' => 'Apoyo técnico',        'color' => 'gray'],
                                                    ];
                                                    $pos = $posLabels[$tech['position']] ?? ['label' => $tech['position'], 'color' => 'gray'];
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                    bg-{{ $pos['color'] }}-100 text-{{ $pos['color'] }}-700">
                                                    {{ $pos['label'] }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-gray-400 text-xs">{{ $tech['notes'] ?? '—' }}</td>
                                            @if ($canEditTecnicos)
                                                <td class="px-5 py-3 text-right space-x-2">
                                                    <button wire:click="openTechForm({{ $tech['id'] }})"
                                                        class="text-indigo-500 hover:text-indigo-700 text-xs">Editar</button>
                                                    <button wire:click="deleteTech({{ $tech['id'] }})"
                                                        wire:confirm="¿Eliminar este técnico?"
                                                        class="text-red-400 hover:text-red-600 text-xs">Eliminar</button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="py-10 text-center text-gray-400 text-sm">No hay técnicos asignados aún.</div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

    @endif {{-- end detail view --}}

</div>
