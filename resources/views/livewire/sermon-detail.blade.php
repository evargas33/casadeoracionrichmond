<div>

  {{-- ── Hero ── --}}
  <section class="relative pt-28 pb-14 overflow-hidden" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 100%);">
    <div class="relative z-10 max-w-4xl mx-auto px-6 md:px-12 text-center">
      @if($sermon->series)
        <a href="{{ route('sermons.index', ['filterSeries' => $sermon->series->id]) }}"
          class="inline-block text-gold/80 text-xs tracking-[.3em] uppercase font-sans mb-4 hover:text-gold transition-colors">
          {{ $sermon->series->title }}
        </a>
      @endif
      <h1 class="font-serif font-light text-4xl md:text-5xl text-white leading-tight mb-4">{{ $sermon->title }}</h1>
      <div class="flex items-center justify-center gap-4 text-white/40 text-sm font-light flex-wrap">
        <span>{{ $sermon->speaker }}</span>
        <span class="w-1 h-1 rounded-full bg-white/20"></span>
        <span>{{ $sermon->date->translatedFormat('j \d\e F, Y') }}</span>
        @if($sermon->bible_passage)
          <span class="w-1 h-1 rounded-full bg-white/20"></span>
          <span class="text-gold/60">{{ $sermon->bible_passage }}</span>
        @endif
        @if($sermon->duration_minutes)
          <span class="w-1 h-1 rounded-full bg-white/20"></span>
          <span>{{ $sermon->duration_minutes }} min</span>
        @endif
      </div>
    </div>
  </section>

  {{-- ── Main content ── --}}
  <section class="py-16 bg-cream">
    <div class="max-w-4xl mx-auto px-6 md:px-12">

      {{-- Back link --}}
      <a href="{{ route('sermons.index') }}" class="inline-flex items-center gap-2 text-navy/40 text-sm hover:text-gold transition-colors mb-10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Todos los sermones
      </a>

      {{-- Video embed (YouTube) --}}
      @if($sermon->video_url)
        @php
          $videoId = null;
          $v = $sermon->video_url;
          // youtube.com/watch?v=ID, youtu.be/ID, youtube.com/live/ID, youtube.com/shorts/ID, youtube.com/embed/ID
          if (preg_match('/(?:youtube\.com\/(?:watch\?v=|live\/|shorts\/|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $v, $m)) {
              $videoId = $m[1];
          }
        @endphp
        @if($videoId)
          <div class="aspect-video w-full mb-10 overflow-hidden shadow-xl">
            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
              width="100%" height="100%" frameborder="0" allowfullscreen
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share">
            </iframe>
          </div>
        @else
          <div class="mb-10">
            <a href="{{ $v }}" target="_blank"
              class="inline-flex items-center gap-3 bg-navy text-white px-6 py-3 text-sm font-light hover:bg-gold hover:text-navy transition-all duration-300">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
              Ver video
            </a>
          </div>
        @endif
      @elseif($sermon->thumbnail)
        <div class="aspect-video w-full mb-10 overflow-hidden shadow-xl">
          <img src="{{ $sermon->thumbnail }}" alt="{{ $sermon->title }}" class="w-full h-full object-cover">
        </div>
      @endif

      {{-- Audio embed (SoundCloud) --}}
      @if($sermon->audio_url)
        @php
          $scEmbedUrl = 'https://w.soundcloud.com/player/?url=' . urlencode($sermon->audio_url)
            . '&color=%23c9a84c&auto_play=false&hide_related=true&show_comments=false'
            . '&show_user=true&show_reposts=false&show_teaser=false';
        @endphp
        <div class="mb-10 overflow-hidden shadow-sm" style="border:1px solid rgba(26,46,74,.08)">
          <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay"
            src="{{ $scEmbedUrl }}">
          </iframe>
        </div>
      @endif

      {{-- Description --}}
      @if($sermon->description)
        <div class="prose prose-lg max-w-none font-light leading-relaxed text-navy/80 mb-12">
          {!! nl2br(e($sermon->description)) !!}
        </div>
      @endif

      {{-- Share --}}
      <div class="flex items-center gap-4 pt-8 border-t border-navy/10">
        <span class="text-navy/40 text-sm font-light">Compartir:</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
          class="text-navy/30 hover:text-gold transition-colors">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
        </a>
        <a href="https://wa.me/?text={{ urlencode($sermon->title . ' - ' . request()->url()) }}" target="_blank"
          class="text-navy/30 hover:text-gold transition-colors">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
      </div>

    </div>
  </section>

  {{-- ── Related ── --}}
  @if($related->isNotEmpty())
    <section class="py-16 bg-navy">
      <div class="max-w-7xl mx-auto px-6 md:px-12">
        <h2 class="font-serif font-light text-2xl text-white mb-8">
          @if($sermon->series) Más de esta serie @else Sermones recientes @endif
        </h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($related as $rel)
            <a href="{{ route('sermons.show', $rel->slug) }}"
              class="group bg-navy-light border border-white/10 overflow-hidden hover:border-gold/30 transition-colors">
              <div class="relative aspect-video overflow-hidden" style="background:#243d61">
                @if($rel->thumbnail)
                  <img src="{{ $rel->thumbnail }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80">
                @else
                  <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gold/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                  </div>
                @endif
              </div>
              <div class="p-4">
                <p class="text-gold text-xs tracking-widest uppercase font-sans mb-1">{{ $rel->series?->title ?? 'Especial' }}</p>
                <h4 class="font-serif text-white text-base font-light group-hover:text-gold transition-colors leading-snug">{{ $rel->title }}</h4>
                <p class="text-white/40 text-xs font-light mt-1">{{ $rel->speaker }} · {{ $rel->date->translatedFormat('j M Y') }}</p>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </section>
  @endif

</div>
