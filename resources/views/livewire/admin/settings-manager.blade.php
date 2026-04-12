<div>
    @if (session('saved'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-5 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Configuración guardada correctamente.
        </div>
    @endif

    <form wire:submit="save" class="space-y-8">

        {{-- ── Identidad ── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <h2 class="text-sm font-semibold text-gray-800">Identidad de la iglesia</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Nombre de la iglesia <span class="text-red-500">*</span></label>
                    <input wire:model="church_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Tagline</label>
                    <input wire:model="church_tagline" type="text" placeholder="Ej. Iglesia Evangélica · Bay Area" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_tagline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Descripción breve</label>
                    <textarea wire:model="church_description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c] resize-none"></textarea>
                    @error('church_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Año de fundación</label>
                    <input wire:model="church_founded" type="text" placeholder="Ej. 2009" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_founded') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Nombre del pastor</label>
                    <input wire:model="pastor_name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('pastor_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Título del pastor</label>
                    <input wire:model="pastor_title" type="text" placeholder="Ej. Pastor Principal" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('pastor_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ── Contacto ── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h2 class="text-sm font-semibold text-gray-800">Contacto y ubicación</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Dirección</label>
                    <input wire:model="church_address" type="text" placeholder="Ej. 1245 Mission Blvd, Suite 4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Ciudad y estado</label>
                    <input wire:model="church_city" type="text" placeholder="Ej. San Francisco, CA 94110" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Teléfono</label>
                    <input wire:model="church_phone" type="text" placeholder="Ej. (415) 555-0192" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Correo electrónico</label>
                    <input wire:model="church_email" type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">URL de Google Maps <span class="text-gray-400 font-normal">(botón "Abrir en Maps")</span></label>
                    <input wire:model="church_maps_url" type="text" placeholder="https://maps.google.com/..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('church_maps_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Embed de Google Maps <span class="text-gray-400 font-normal">(src del iframe)</span></label>
                    <input wire:model="church_maps_embed" type="text" placeholder="https://www.google.com/maps/embed?pb=..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    <p class="text-gray-400 text-xs mt-1">En Google Maps → Compartir → Incorporar un mapa → copia solo el valor del atributo <code>src</code>.</p>
                    @error('church_maps_embed') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ── Horarios ── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                <h2 class="text-sm font-semibold text-gray-800">Horarios de servicios</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Domingo</label>
                    <input wire:model="schedule_sunday" type="text" placeholder="Ej. 10:00 am" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('schedule_sunday') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Sábado</label>
                    <input wire:model="schedule_saturday" type="text" placeholder="Ej. 7:00 pm" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('schedule_saturday') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Viernes</label>
                    <input wire:model="schedule_friday" type="text" placeholder="Ej. 7:30 pm" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('schedule_friday') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ── Redes sociales ── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <circle cx="18" cy="5" r="3" />
                    <circle cx="6" cy="12" r="3" />
                    <circle cx="18" cy="19" r="3" />
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                </svg>
                <h2 class="text-sm font-semibold text-gray-800">Redes sociales</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Facebook</label>
                    <input wire:model="social_facebook" type="url" placeholder="https://facebook.com/..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('social_facebook') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Instagram</label>
                    <input wire:model="social_instagram" type="url" placeholder="https://instagram.com/..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('social_instagram') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">YouTube</label>
                    <input wire:model="social_youtube" type="url" placeholder="https://youtube.com/..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('social_youtube') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ── Transmisión en vivo ── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/>
                    <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/>
                </svg>
                <h2 class="text-sm font-semibold text-gray-800">Transmisión en vivo</h2>
            </div>
            <div class="p-6 space-y-5">
                {{-- Toggle activo --}}
                <div class="flex items-center justify-between p-4 rounded-xl border-2 transition-colors {{ $live_stream_active ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }}">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Estado de la transmisión</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $live_stream_active
                                ? '🔴 EN VIVO — visible en el sitio público'
                                : 'Sin transmisión activa' }}
                        </p>
                    </div>
                    <button type="button" wire:click="$set('live_stream_active', {{ $live_stream_active ? 'false' : 'true' }})"
                        class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors focus:outline-none
                            {{ $live_stream_active ? 'bg-red-500' : 'bg-gray-300' }}">
                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform
                            {{ $live_stream_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>

                {{-- Video ID --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">
                        ID del video de YouTube
                        <span class="text-gray-400 font-normal ml-1">— la parte final de la URL del live</span>
                    </label>
                    <input wire:model="live_stream_video_id" type="text"
                        placeholder="Ej. dQw4w9WgXcQ"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    <p class="text-gray-400 text-xs mt-1.5">
                        De <span class="font-mono">youtube.com/watch?v=<strong class="text-gray-600">dQw4w9WgXcQ</strong></span>
                        o <span class="font-mono">youtube.com/live/<strong class="text-gray-600">dQw4w9WgXcQ</strong></span>
                        — copia solo el ID.
                    </p>
                    @error('live_stream_video_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @if($live_stream_active && $live_stream_video_id)
                    <div class="flex items-center gap-2 text-xs text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2.5">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse flex-shrink-0"></span>
                        La página <strong>/en-vivo</strong> está activa y mostrando el stream al público.
                    </div>
                @endif
            </div>
        </div>

        {{-- ── SEO ── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <h2 class="text-sm font-semibold text-gray-800">SEO / Meta tags</h2>
            </div>
            <div class="p-6 grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Meta título</label>
                    <input wire:model="meta_title" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c]">
                    @error('meta_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Meta descripción</label>
                    <textarea wire:model="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a84c]/40 focus:border-[#c9a84c] resize-none"></textarea>
                    @error('meta_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Save button --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-[#c9a84c] hover:bg-[#b8963e] text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Guardar configuración
            </button>
        </div>

    </form>
</div>
