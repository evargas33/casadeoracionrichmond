<div class="space-y-4">

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <div class="flex gap-3 flex-wrap">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar usuario..."
                class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c] w-56"
            >

            <select wire:model.live="filterRole"
                class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c]">
                <option value="">Todos los roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>

        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
        <button wire:click="openCreate"
            class="flex items-center gap-2 px-4 py-1.5 bg-[#1a2e4a] text-white text-sm rounded-lg hover:bg-[#c9a84c] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo usuario
        </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Roles</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Registrado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    {{-- Avatar + info --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#1a2e4a] flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-white text-xs font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="text-[10px] text-[#c9a84c] font-medium">(tú)</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Roles --}}
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @forelse($user->roles as $role)
                                <span class="px-2 py-0.5 text-[11px] rounded-full font-medium
                                    {{ $role->name === 'superadmin' ? 'bg-red-100 text-red-700' :
                                       ($role->name === 'admin'     ? 'bg-[#1a2e4a]/10 text-[#1a2e4a]' :
                                       ($role->name === 'editor'    ? 'bg-[#c9a84c]/15 text-[#c9a84c]' :
                                                                       'bg-gray-100 text-gray-500')) }}">
                                    {{ $role->display_name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-300">Sin rol</span>
                            @endforelse
                        </div>
                    </td>

                    {{-- Estado --}}
                    <td class="px-4 py-3">
                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']) && $user->id !== auth()->id())
                            <button wire:click="toggleActive({{ $user->id }})"
                                class="px-2.5 py-0.5 text-xs rounded-full font-medium transition-colors
                                    {{ ($user->is_active ?? true) ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                {{ ($user->is_active ?? true) ? 'Activo' : 'Inactivo' }}
                            </button>
                        @else
                            <span class="px-2.5 py-0.5 text-xs rounded-full font-medium
                                {{ ($user->is_active ?? true) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ ($user->is_active ?? true) ? 'Activo' : 'Inactivo' }}
                            </span>
                        @endif
                    </td>

                    {{-- Fecha --}}
                    <td class="px-4 py-3 text-xs text-gray-400">
                        {{ $user->created_at->format('d M Y') }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2 justify-end">
                            @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
                            <button wire:click="openEdit({{ $user->id }})"
                                class="p-1.5 text-gray-400 hover:text-[#1a2e4a] hover:bg-gray-100 rounded-md transition-colors"
                                title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>

                            @if(auth()->user()->hasRole('superadmin') && $user->id !== auth()->id())
                            <button
                                wire:click="delete({{ $user->id }})"
                                wire:confirm="¿Eliminar a {{ $user->name }}? Esta acción no se puede deshacer."
                                class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors"
                                title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400">
                        No se encontraron usuarios.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" wire:click="$set('showModal', false)"></div>

        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-800">
                    {{ $isEditing ? 'Editar usuario' : 'Nuevo usuario' }}
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
                    <input wire:model="name" type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c]">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                    <input wire:model="email" type="email"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c]">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Contraseña {{ $isEditing ? '(dejar vacío para no cambiar)' : '' }}
                    </label>
                    <input wire:model="password" type="password"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#c9a84c]"
                        placeholder="{{ $isEditing ? '••••••••' : 'Mínimo 8 caracteres' }}">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Roles --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2">Roles</label>
                    <div class="space-y-1.5">
                        @foreach($roles as $role)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="selectedRoles"
                                value="{{ $role->id }}"
                                class="rounded border-gray-300 text-[#1a2e4a] focus:ring-[#c9a84c]"
                            >
                            <span class="text-sm text-gray-700">{{ $role->display_name }}</span>
                            <span class="text-xs text-gray-400">— {{ $role->description }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('selectedRoles') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Estado --}}
                <div class="flex items-center gap-2">
                    <input wire:model="is_active" type="checkbox" id="is_active"
                        class="rounded border-gray-300 text-[#1a2e4a] focus:ring-[#c9a84c]">
                    <label for="is_active" class="text-sm text-gray-700 cursor-pointer">Usuario activo</label>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-1.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-1.5 text-sm bg-[#1a2e4a] text-white rounded-lg hover:bg-[#c9a84c] transition-colors">
                        {{ $isEditing ? 'Actualizar' : 'Crear usuario' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
    @endif

</div>
