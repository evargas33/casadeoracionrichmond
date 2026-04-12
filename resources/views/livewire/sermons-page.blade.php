<div>

  {{-- ── Hero ── --}}
  <section class="relative pt-32 pb-20 overflow-hidden" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 60%, #243d61 100%);">
    <div class="absolute inset-0 opacity-5" style="background-image: url('https://picsum.photos/seed/sermons-bg/1600/600'); background-size: cover; background-position: center;"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 text-center">
      <p class="text-gold/80 text-xs tracking-[.35em] uppercase font-sans mb-4">Palabra de vida</p>
      <h1 class="font-serif font-light text-5xl md:text-6xl text-white leading-tight mb-4">
        Sermones <em class="italic text-gold">recientes</em>
      </h1>
      <p class="text-white/50 font-light text-base max-w-lg mx-auto">
        Escucha y comparte la Palabra predicada cada semana en nuestra congregación.
      </p>
    </div>
  </section>

  {{-- ── Filters ── --}}
  <div class="sticky top-[64px] z-20 bg-cream/95 backdrop-blur border-b border-navy/10 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-3 flex flex-col sm:flex-row gap-3">

      {{-- Search --}}
      <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por título, predicador o pasaje..."
          class="w-full pl-9 pr-4 py-2.5 bg-white border border-navy/15 text-navy text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors">
      </div>

      {{-- Series filter --}}
      <select wire:model.live="filterSeries"
        class="bg-white border border-navy/15 text-navy text-sm font-light px-4 py-2.5 focus:outline-none focus:border-gold transition-colors min-w-[180px]">
        <option value="">Todas las series</option>
        @foreach($allSeries as $serie)
          <option value="{{ $serie->id }}">{{ $serie->title }}</option>
        @endforeach
      </select>

      {{-- Count --}}
      <div class="flex items-center text-navy/40 text-sm font-light whitespace-nowrap self-center">
        {{ $sermons->total() }} {{ $sermons->total() === 1 ? 'sermón' : 'sermones' }}
      </div>

    </div>
  </div>

  {{-- ── Grid ── --}}
  <section class="py-16">
    <div class="max-w-7xl mx-auto px-6 md:px-12">

      @if($sermons->isEmpty())
        <div class="text-center py-24">
          <svg class="w-12 h-12 text-navy/20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
          <p class="font-serif text-navy/40 text-xl font-light">No se encontraron sermones</p>
          @if($search || $filterSeries)
            <button wire:click="$set('search', ''); $set('filterSeries', '')" class="mt-4 text-gold text-sm hover:underline">Limpiar filtros</button>
          @endif
        </div>
      @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($sermons as $sermon)
            <article class="card-hover bg-white overflow-hidden fade-in group" style="border:1px solid rgba(26,46,74,.08)">

              {{-- Thumbnail --}}
              <a href="{{ route('sermons.show', $sermon->slug) }}" class="block relative overflow-hidden aspect-video bg-navy">
                @if($sermon->thumbnail)
                  <img src="{{ $sermon->thumbnail }}" alt="{{ $sermon->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                  <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #1a2e4a, #243d61)">
                    <svg class="w-10 h-10 text-gold/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                  </div>
                @endif

                {{-- Play overlay --}}
                <div class="absolute inset-0 bg-navy/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <div class="w-12 h-12 rounded-full bg-gold/90 flex items-center justify-center">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><polygon points="5,3 19,12 5,21"/></svg>
                  </div>
                </div>

                @if($sermon->duration_minutes)
                  <div class="absolute bottom-2 right-2 bg-navy/80 text-white text-xs px-2 py-0.5 font-light">{{ $sermon->duration_minutes }} min</div>
                @endif
              </a>

              {{-- Body --}}
              <div class="p-5">
                @if($sermon->series)
                  <a href="{{ route('sermons.index', ['filterSeries' => $sermon->series->id]) }}"
                    class="text-gold text-xs tracking-widest uppercase font-sans hover:underline">{{ $sermon->series->title }}</a>
                @endif

                <a href="{{ route('sermons.show', $sermon->slug) }}">
                  <h3 class="font-serif text-navy text-lg font-light mt-1 mb-1 hover:text-gold transition-colors leading-snug">{{ $sermon->title }}</h3>
                </a>

                <p class="text-navy/50 text-xs font-light mb-2">{{ $sermon->speaker }} · {{ $sermon->date->translatedFormat('j M Y') }}</p>

                @if($sermon->bible_passage)
                  <p class="text-navy/40 text-xs mb-3 flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                    {{ $sermon->bible_passage }}
                  </p>
                @endif

                @if($sermon->description)
                  <p class="text-navy/60 text-sm font-light leading-relaxed mb-4 line-clamp-2">{{ $sermon->description }}</p>
                @endif

                <div class="flex items-center gap-3 pt-3 border-t border-navy/8">
                  @if($sermon->audio_url)
                    <a href="{{ $sermon->audio_url }}" target="_blank"
                      class="flex items-center gap-1.5 text-xs text-navy/50 hover:text-gold transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                      Audio
                    </a>
                  @endif
                  @if($sermon->video_url)
                    <a href="{{ $sermon->video_url }}" target="_blank"
                      class="flex items-center gap-1.5 text-xs text-navy/50 hover:text-gold transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                      Video
                    </a>
                  @endif
                  <a href="{{ route('sermons.show', $sermon->slug) }}" class="ml-auto text-gold text-xs tracking-wide hover:underline">Ver más →</a>
                </div>
              </div>
            </article>
          @endforeach
        </div>

        {{-- Pagination --}}
        @if($sermons->hasPages())
          <div class="mt-12 flex justify-center">
            {{ $sermons->links() }}
          </div>
        @endif
      @endif

    </div>
  </section>

</div>
