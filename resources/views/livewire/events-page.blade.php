<div>

  {{-- ── Hero ── --}}
  <section class="relative pt-32 pb-20 overflow-hidden" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 60%, #243d61 100%);">
    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 text-center">
      <p class="text-gold/80 text-xs tracking-[.35em] uppercase font-sans mb-4">Agenda</p>
      <h1 class="font-serif font-light text-5xl md:text-6xl text-white leading-tight mb-4">
        Próximos <em class="italic text-gold">eventos</em>
      </h1>
      <p class="text-white/50 font-light text-base max-w-lg mx-auto">
        Actividades, retiros y celebraciones especiales de nuestra congregación.
      </p>
    </div>
  </section>

  {{-- ── Filters ── --}}
  <div class="sticky top-[64px] z-20 bg-cream/95 backdrop-blur border-b border-navy/10 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-3 flex flex-col sm:flex-row gap-3">

      {{-- Tabs --}}
      <div class="flex border border-navy/15 overflow-hidden">
        <button wire:click="$set('tab', 'upcoming')"
          class="px-4 py-2 text-xs font-medium tracking-wide transition-colors {{ $tab === 'upcoming' ? 'bg-navy text-white' : 'text-navy/50 hover:text-navy' }}">
          Próximos
        </button>
        <button wire:click="$set('tab', 'past')"
          class="px-4 py-2 text-xs font-medium tracking-wide transition-colors {{ $tab === 'past' ? 'bg-navy text-white' : 'text-navy/50 hover:text-navy' }}">
          Pasados
        </button>
      </div>

      {{-- Search --}}
      <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar eventos..."
          class="w-full pl-9 pr-4 py-2.5 bg-white border border-navy/15 text-navy text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors">
      </div>

      {{-- Category filter --}}
      <select wire:model.live="filterCategory"
        class="bg-white border border-navy/15 text-navy text-sm font-light px-4 py-2.5 focus:outline-none focus:border-gold transition-colors min-w-[160px]">
        <option value="">Todas las categorías</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>

      <div class="flex items-center text-navy/40 text-sm font-light whitespace-nowrap self-center">
        {{ $events->total() }} {{ $events->total() === 1 ? 'evento' : 'eventos' }}
      </div>
    </div>
  </div>

  {{-- ── Grid ── --}}
  <section class="py-16">
    <div class="max-w-7xl mx-auto px-6 md:px-12">

      @if($events->isEmpty())
        <div class="text-center py-24">
          <svg class="w-12 h-12 text-navy/20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <p class="font-serif text-navy/40 text-xl font-light">
            {{ $tab === 'upcoming' ? 'No hay eventos próximos' : 'No hay eventos pasados' }}
          </p>
          @if($search || $filterCategory)
            <button wire:click="$set('search',''); $set('filterCategory','')" class="mt-4 text-gold text-sm hover:underline">Limpiar filtros</button>
          @endif
        </div>
      @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($events as $event)
            @php
              $daysLeft = now()->diffInDays($event->start_date, false);
              if ($event->featured) {
                  $badgeText = 'Destacado';
                  $badgeClass = 'bg-gold text-navy';
              } elseif ($daysLeft >= 0 && $daysLeft <= 7) {
                  $badgeText = 'Esta semana';
                  $badgeClass = 'bg-gold text-navy';
              } elseif ($daysLeft > 7 && $daysLeft <= 31) {
                  $badgeText = 'Este mes';
                  $badgeClass = 'bg-white/10 border border-white/20 text-white';
              } else {
                  $badgeText = $event->start_date->translatedFormat('F Y');
                  $badgeClass = 'bg-white/10 border border-white/20 text-white';
              }
            @endphp
            <article class="card-hover border border-white/10 overflow-hidden fade-in group" style="background:#243d61">
              <a href="{{ route('events.show', $event->slug) }}" class="block relative overflow-hidden aspect-[16/9]">
                @if($event->image)
                  <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                  <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#1a2e4a,#243d61)">
                    <svg class="w-10 h-10 text-gold/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  </div>
                @endif
                <div class="absolute top-3 left-3 text-xs px-3 py-1 font-medium tracking-wide {{ $badgeClass }}">{{ $badgeText }}</div>
              </a>

              <div class="p-6">
                <div class="flex items-center gap-2 text-gold/70 text-xs tracking-widest uppercase mb-3 font-sans">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  @if($event->all_day)
                    {{ $event->start_date->translatedFormat('j \d\e F') }}
                    @if($event->end_date && $event->end_date->format('Y-m-d') !== $event->start_date->format('Y-m-d'))
                      – {{ $event->end_date->translatedFormat('j \d\e F') }}
                    @endif
                  @else
                    {{ $event->start_date->translatedFormat('j \d\e F · g:i a') }}
                  @endif
                </div>

                <h3 class="font-serif text-white text-xl font-light mb-2">{{ $event->title }}</h3>
                <p class="text-white/50 text-sm font-light leading-relaxed mb-5 line-clamp-2">
                  {{ $event->short_description ?: $event->description }}
                </p>

                <div class="flex items-center justify-between gap-2">
                  @if($event->location)
                    <span class="flex items-center gap-1.5 text-white/40 text-xs truncate">
                      <svg class="flex-shrink-0" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                      {{ $event->location }}
                    </span>
                  @else
                    <span></span>
                  @endif
                  <div class="flex items-center gap-2 flex-shrink-0">
                    @if($event->requires_registration)
                      @php
                        $isFull = $event->capacity && $event->registrations_count >= $event->capacity;
                      @endphp
                      <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $isFull ? 'bg-red-500/20 text-red-300' : 'bg-emerald-500/20 text-emerald-300' }}">
                        {{ $isFull ? 'Cupo lleno' : 'Inscripción' }}
                      </span>
                    @endif
                    <a href="{{ route('events.show', $event->slug) }}" class="text-gold text-xs tracking-wide hover:underline">Ver más →</a>
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </div>

        @if($events->hasPages())
          <div class="mt-12 flex justify-center">
            {{ $events->links() }}
          </div>
        @endif
      @endif

    </div>
  </section>

</div>
