<div>

  {{-- ── Hero ── --}}
  <section class="relative pt-28 pb-14 overflow-hidden" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 100%);">
    <div class="relative z-10 max-w-5xl mx-auto px-6 md:px-12 text-center">
      @if($event->category)
        <span class="inline-block text-gold/80 text-xs tracking-[.3em] uppercase font-sans mb-4">{{ $event->category->name }}</span>
      @endif
      <h1 class="font-serif font-light text-4xl md:text-5xl text-white leading-tight mb-5">{{ $event->title }}</h1>

      <div class="flex items-center justify-center gap-4 text-white/50 text-sm font-light flex-wrap">
        <span class="flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          @if($event->all_day)
            {{ $event->start_date->translatedFormat('l j \d\e F, Y') }}
            @if($event->end_date && $event->end_date->format('Y-m-d') !== $event->start_date->format('Y-m-d'))
              – {{ $event->end_date->translatedFormat('l j \d\e F') }}
            @endif
          @else
            {{ $event->start_date->translatedFormat('l j \d\e F, Y · g:i a') }}
            @if($event->end_date) – {{ $event->end_date->translatedFormat('g:i a') }} @endif
          @endif
        </span>
        @if($event->location)
          <span class="w-1 h-1 rounded-full bg-white/20"></span>
          <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ $event->location }}
          </span>
        @endif
        @if($event->capacity)
          <span class="w-1 h-1 rounded-full bg-white/20"></span>
          @php $pct = min(100, round($registrationsCount / $event->capacity * 100)); @endphp
          <span class="{{ $pct >= 100 ? 'text-red-400' : ($pct >= 80 ? 'text-amber-400' : 'text-white/50') }}">
            {{ $seatsLeft === 0 ? 'Cupo lleno' : $seatsLeft . ' lugares disponibles' }}
          </span>
        @endif
      </div>
    </div>
  </section>

  {{-- ── Body ── --}}
  <section class="py-12 bg-cream">
    <div class="max-w-5xl mx-auto px-6 md:px-12">

      <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-navy/40 text-sm hover:text-gold transition-colors mb-10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Todos los eventos
      </a>

      <div class="grid lg:grid-cols-3 gap-10">

        {{-- ── Columna izquierda: contenido ── --}}
        <div class="lg:col-span-2 space-y-8">

          {{-- Image --}}
          @if($event->image)
            <div class="aspect-[16/9] w-full overflow-hidden shadow-xl">
              <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            </div>
          @endif

          {{-- Info cards --}}
          <div class="grid sm:grid-cols-2 gap-4">

            {{-- Fecha --}}
            <div class="bg-white border border-navy/10 p-5 flex gap-4 items-start">
              <div class="w-9 h-9 rounded-full bg-navy flex items-center justify-center flex-shrink-0">
                <svg width="14" height="14" fill="none" stroke="#c9a84c" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </div>
              <div>
                <p class="text-xs text-navy/40 uppercase tracking-widest font-sans mb-1">Fecha y hora</p>
                @if($event->all_day)
                  <p class="text-navy text-sm font-light">{{ $event->start_date->translatedFormat('l j \d\e F, Y') }}</p>
                  @if($event->end_date && $event->end_date->format('Y-m-d') !== $event->start_date->format('Y-m-d'))
                    <p class="text-navy/60 text-sm font-light">hasta {{ $event->end_date->translatedFormat('l j \d\e F') }}</p>
                  @endif
                  <p class="text-gold text-xs mt-1">Todo el día</p>
                @else
                  <p class="text-navy text-sm font-light">{{ $event->start_date->translatedFormat('l j \d\e F, Y') }}</p>
                  <p class="text-navy/60 text-sm font-light">
                    {{ $event->start_date->translatedFormat('g:i a') }}
                    @if($event->end_date) – {{ $event->end_date->translatedFormat('g:i a') }} @endif
                  </p>
                @endif
              </div>
            </div>

            {{-- Lugar --}}
            @if($event->location || $event->address)
              <div class="bg-white border border-navy/10 p-5 flex gap-4 items-start">
                <div class="w-9 h-9 rounded-full bg-navy flex items-center justify-center flex-shrink-0">
                  <svg width="14" height="14" fill="none" stroke="#c9a84c" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                  <p class="text-xs text-navy/40 uppercase tracking-widest font-sans mb-1">Lugar</p>
                  @if($event->location)<p class="text-navy text-sm font-light">{{ $event->location }}</p>@endif
                  @if($event->address)<p class="text-navy/60 text-sm font-light">{{ $event->address }}</p>@endif
                  @if($event->maps_url)
                    <a href="{{ $event->maps_url }}" target="_blank" class="text-gold text-xs hover:underline mt-1 inline-block">Ver en Google Maps →</a>
                  @endif
                </div>
              </div>
            @endif

            {{-- Cupo --}}
            @if($event->capacity)
              @php $pct = min(100, round($registrationsCount / $event->capacity * 100)); @endphp
              <div class="bg-white border border-navy/10 p-5 flex gap-4 items-start sm:col-span-2">
                <div class="w-9 h-9 rounded-full bg-navy flex items-center justify-center flex-shrink-0">
                  <svg width="14" height="14" fill="none" stroke="#c9a84c" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="flex-1">
                  <p class="text-xs text-navy/40 uppercase tracking-widest font-sans mb-1">Cupo</p>
                  <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-navy text-sm font-light">
                      <strong class="font-semibold">{{ $registrationsCount }}</strong> / {{ $event->capacity }} inscritos
                    </span>
                    @if($seatsLeft === 0)
                      <span class="text-xs text-red-500 font-medium">Cupo lleno</span>
                    @else
                      <span class="text-xs text-navy/40">· {{ $seatsLeft }} lugares disponibles</span>
                    @endif
                  </div>
                  <div class="w-full bg-navy/10 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full transition-all
                      {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-amber-400' : 'bg-emerald-500') }}"
                      style="width: {{ $pct }}%"></div>
                  </div>
                </div>
              </div>
            @endif

          </div>

          {{-- Description --}}
          @if($event->description)
            <div class="bg-white border border-navy/10 p-6">
              <h2 class="font-serif text-navy text-xl font-light mb-4">Acerca del evento</h2>
              <div class="prose max-w-none font-light leading-relaxed text-navy/80 text-sm">
                {!! nl2br(e($event->description)) !!}
              </div>
            </div>
          @endif

          {{-- Mapa --}}
          @if($event->maps_url && ($event->address || $event->location))
            <div class="bg-white border border-navy/10 overflow-hidden">
              <div class="px-5 py-3 border-b border-navy/10 flex items-center justify-between">
                <p class="text-xs text-navy/40 uppercase tracking-widest font-sans">Cómo llegar</p>
                <a href="{{ $event->maps_url }}" target="_blank"
                  class="text-xs text-gold hover:underline flex items-center gap-1">
                  Abrir en Maps
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </a>
              </div>
              @php
                $mapQuery = urlencode(($event->address ?: '') . ' ' . ($event->location ?: ''));
              @endphp
              <iframe
                src="https://maps.google.com/maps?q={{ $mapQuery }}&output=embed&hl=es"
                width="100%" height="280" frameborder="0"
                style="border:0; display:block" allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          @endif

          {{-- Share --}}
          <div class="flex items-center gap-4 pt-2 border-t border-navy/10">
            <span class="text-navy/40 text-sm font-light">Compartir:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="text-navy/30 hover:text-gold transition-colors">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            </a>
            <a href="https://wa.me/?text={{ urlencode($event->title . ' — ' . request()->url()) }}" target="_blank" class="text-navy/30 hover:text-gold transition-colors">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
          </div>

        </div>

        {{-- ── Columna derecha: formulario sticky ── --}}
        <div class="lg:col-span-1">
          <div class="sticky top-24 space-y-4">

            {{-- Formulario de inscripción --}}
            @php $isPast = $event->start_date->isPast(); @endphp

            <div class="border border-navy/10 overflow-hidden bg-white">
              <div class="bg-navy px-5 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                  <h3 class="font-serif text-white text-lg font-light leading-tight">Confirmar asistencia</h3>
                  <p class="text-white/40 text-xs font-light">Déjanos saber que asistirás</p>
                </div>
              </div>

              <div class="p-5">

                @if($isPast)
                  <p class="text-navy/50 text-sm font-light text-center py-4">Este evento ya pasó.</p>

                @elseif($seatsLeft === 0)
                  <div class="text-center py-4">
                    <p class="text-red-500 text-sm font-medium mb-1">Cupo lleno</p>
                    <p class="text-navy/50 text-xs font-light">No hay lugares disponibles.</p>
                  </div>

                @elseif($submitted)
                  <div class="text-center py-4">
                    <div class="w-12 h-12 rounded-full bg-green-50 border border-green-200 flex items-center justify-center mx-auto mb-3">
                      <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="font-serif text-navy text-lg font-light mb-1">¡Registro confirmado!</p>
                    <p class="text-navy/50 text-xs font-light">Gracias <strong class="text-navy font-medium">{{ $reg_name }}</strong>, te esperamos.</p>
                  </div>

                @elseif($alreadyRegistered)
                  <div class="text-center py-4">
                    <div class="w-12 h-12 rounded-full bg-gold/10 border border-gold/30 flex items-center justify-center mx-auto mb-3">
                      <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="font-serif text-navy text-lg font-light mb-1">Ya estás registrado</p>
                    <p class="text-navy/50 text-xs font-light">Ya existe un registro con ese correo.</p>
                  </div>

                @else
                  <form wire:submit="register" class="space-y-3">

                    <div>
                      <label class="block text-xs font-medium text-navy/60 mb-1">Nombre completo <span class="text-red-500">*</span></label>
                      <input wire:model="reg_name" type="text" placeholder="Tu nombre"
                        class="w-full border border-navy/15 px-3 py-2 text-navy text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors">
                      @error('reg_name') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                      <label class="block text-xs font-medium text-navy/60 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
                      <input wire:model="reg_email" type="email" placeholder="tu@correo.com"
                        class="w-full border border-navy/15 px-3 py-2 text-navy text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors">
                      @error('reg_email') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                      <label class="block text-xs font-medium text-navy/60 mb-1">Teléfono <span class="text-navy/30 font-normal">(opcional)</span></label>
                      <input wire:model="reg_phone" type="tel" placeholder="(555) 000-0000"
                        class="w-full border border-navy/15 px-3 py-2 text-navy text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors">
                    </div>

                    <div>
                      <label class="block text-xs font-medium text-navy/60 mb-1">¿Cuántas personas?</label>
                      <select wire:model="reg_attendees"
                        class="w-full border border-navy/15 px-3 py-2 text-navy text-sm font-light focus:outline-none focus:border-gold transition-colors bg-white">
                        @for($i = 1; $i <= min(10, $seatsLeft ?? 10); $i++)
                          <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'persona' : 'personas' }}</option>
                        @endfor
                      </select>
                    </div>

                    <div>
                      <label class="block text-xs font-medium text-navy/60 mb-1">Nota <span class="text-navy/30 font-normal">(opcional)</span></label>
                      <textarea wire:model="reg_notes" rows="2" placeholder="¿Algo que debamos saber?"
                        class="w-full border border-navy/15 px-3 py-2 text-navy text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors resize-none"></textarea>
                    </div>

                    <button type="submit"
                      class="w-full flex items-center justify-center gap-2 bg-navy text-white px-6 py-3 text-sm font-light tracking-wide hover:bg-gold hover:text-navy transition-all duration-300 mt-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      Confirmar asistencia
                    </button>

                  </form>
                @endif

              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- ── Eventos relacionados ── --}}
  @if($related->isNotEmpty())
    <section class="py-16 bg-navy">
      <div class="max-w-5xl mx-auto px-6 md:px-12">
        <h2 class="font-serif font-light text-2xl text-white mb-8">Otros eventos próximos</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($related as $rel)
            <a href="{{ route('events.show', $rel->slug) }}" class="group border border-white/10 overflow-hidden hover:border-gold/30 transition-colors" style="background:#243d61">
              <div class="relative aspect-video overflow-hidden">
                @if($rel->image)
                  <img src="{{ $rel->image }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80">
                @else
                  <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gold/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  </div>
                @endif
              </div>
              <div class="p-4">
                <p class="text-gold text-xs tracking-widest uppercase font-sans mb-1">{{ $rel->start_date->translatedFormat('j \d\e F') }}</p>
                <h4 class="font-serif text-white text-base font-light group-hover:text-gold transition-colors">{{ $rel->title }}</h4>
                @if($rel->location)<p class="text-white/40 text-xs font-light mt-1">{{ $rel->location }}</p>@endif
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </section>
  @endif

</div>
