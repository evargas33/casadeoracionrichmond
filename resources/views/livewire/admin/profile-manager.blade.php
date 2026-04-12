<div class="max-w-3xl space-y-6">

    {{-- ── Header con avatar ── --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center gap-5">

            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                <div class="w-20 h-20 rounded-full overflow-hidden bg-[#1a2e4a] border-2 border-[#c9a84c]/30 flex items-center justify-center">
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="{{ $name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-[#c9a84c] text-2xl font-semibold">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <button type="button"
                    wire:click="$dispatch('openMediaPicker', { field: 'avatar' })"
                    class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-[#1a2e4a] border-2 border-white flex items-center justify-center hover:bg-[#c9a84c] transition-colors"
                    title="Cambiar foto">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                </button>
            </div>

            {{-- Info --}}
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ auth()->user()->name }}</h1>
                <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
                <div class="flex flex-wrap gap-1 mt-1.5">
                    @foreach(auth()->user()->roles as $role)
                        <span class="px-2 py-0.5 text-[11px] rounded-full font-medium
                            {{ $role->name === 'superadmin' ? 'bg-red-100 text-red-700' :
                               ($role->name === 'admin'     ? 'bg-[#1a2e4a]/10 text-[#1a2e4a]' :
                               ($role->name === 'editor'    ? 'bg-[#c9a84c]/15 text-[#a8892f]' :
                                                               'bg-gray-100 text-gray-500')) }}">
                            {{ $role->display_name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ── Información personal ── --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h2 class="text-sm font-semibold text-gray-800">Información personal</h2>
        </div>

        <form wire:submit="saveInfo" class="p-6 space-y-4">

            @if($savedInfo)
                <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5 rounded-lg">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perfil actualizado correctamente.
                </div>
            @endif

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Nombre completo *</label>
                    <input wire:model="name" type="text"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Correo electrónico *</label>
                    <input wire:model="email" type="email"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Avatar --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Foto de perfil</label>
                <div class="flex gap-3 items-center">
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="Avatar preview" class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0">
                    @endif
                    <div class="flex gap-2">
                        <button type="button"
                            wire:click="$dispatch('openMediaPicker', { field: 'avatar' })"
                            class="flex items-center gap-2 border border-dashed border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-500 hover:border-[#c9a84c] hover:text-[#c9a84c] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                            {{ $avatar ? 'Cambiar foto' : 'Seleccionar de la biblioteca' }}
                        </button>
                        @if($avatar)
                            <button type="button" wire:click="$set('avatar', '')"
                                class="text-xs text-red-400 hover:text-red-600 px-2">
                                Quitar
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#1a2e4a] hover:bg-[#c9a84c] text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>

    {{-- ── Cambiar contraseña ── --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
            <h2 class="text-sm font-semibold text-gray-800">Cambiar contraseña</h2>
        </div>

        <form wire:submit="savePassword" class="p-6 space-y-4">

            @if($savedPassword)
                <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2.5 rounded-lg">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Contraseña actualizada correctamente.
                </div>
            @endif

            @if($passwordError)
                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2.5 rounded-lg">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ $passwordError }}
                </div>
            @endif

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Contraseña actual *</label>
                <input wire:model="current_password" type="password"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]"
                    placeholder="••••••••">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Nueva contraseña *</label>
                    <input wire:model="new_password" type="password"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]"
                        placeholder="Mínimo 8 caracteres">
                    @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Confirmar contraseña *</label>
                    <input wire:model="confirm_password" type="password"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]"
                        placeholder="Repetir nueva contraseña">
                    @error('confirm_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#1a2e4a] hover:bg-[#c9a84c] text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    Cambiar contraseña
                </button>
            </div>

        </form>
    </div>

</div>
