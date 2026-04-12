{{-- Template: visit-us — Visítanos --}}

{{-- Hero --}}
<section class="pt-32 pb-14" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 100%);">
  <div class="max-w-4xl mx-auto px-6 md:px-12 text-center">
    <p class="text-gold/80 text-xs tracking-[.35em] uppercase font-sans mb-4">Te esperamos</p>
    <h1 class="font-serif font-light text-4xl md:text-5xl text-white leading-tight mb-4">{{ $page->title }}</h1>
    @if($page->meta_description)
      <p class="text-white/60 font-light max-w-xl mx-auto">{{ $page->meta_description }}</p>
    @endif
  </div>
</section>

<section class="py-16 bg-cream">
  <div class="max-w-5xl mx-auto px-6 md:px-12 space-y-14">

    {{-- Horarios --}}
    @if($settings['schedule_sunday'] || $settings['schedule_saturday'] || $settings['schedule_friday'])
      <div>
        <h2 class="font-serif text-navy text-2xl font-light gold-line mb-6">Horarios de servicios</h2>
        <div class="grid sm:grid-cols-3 gap-4">
          @if($settings['schedule_sunday'])
            <div class="bg-white border border-navy/10 p-6 text-center">
              <p class="text-xs text-navy/40 uppercase tracking-widest font-sans mb-2">Domingo</p>
              <p class="font-serif text-navy text-2xl font-light">{{ $settings['schedule_sunday'] }}</p>
            </div>
          @endif
          @if($settings['schedule_saturday'])
            <div class="bg-white border border-navy/10 p-6 text-center">
              <p class="text-xs text-navy/40 uppercase tracking-widest font-sans mb-2">Sábado</p>
              <p class="font-serif text-navy text-2xl font-light">{{ $settings['schedule_saturday'] }}</p>
            </div>
          @endif
          @if($settings['schedule_friday'])
            <div class="bg-white border border-navy/10 p-6 text-center">
              <p class="text-xs text-navy/40 uppercase tracking-widest font-sans mb-2">Viernes</p>
              <p class="font-serif text-navy text-2xl font-light">{{ $settings['schedule_friday'] }}</p>
            </div>
          @endif
        </div>
      </div>
    @endif

    {{-- Contenido libre --}}
    @if($page->content)
      <div class="prose prose-lg max-w-none font-light leading-relaxed text-navy/80 page-content">
        {!! $page->content !!}
      </div>
    @endif

    {{-- Dirección + Mapa --}}
    @if($settings['church_address'] || $settings['church_maps_embed'])
      <div>
        <h2 class="font-serif text-navy text-2xl font-light gold-line mb-6">Dónde encontrarnos</h2>
        <div class="grid lg:grid-cols-2 gap-8 items-start">

          <div class="space-y-5">
            @if($settings['church_address'])
              <div class="flex gap-4 items-start bg-white border border-navy/10 p-5">
                <div class="w-10 h-10 rounded-full bg-navy flex items-center justify-center flex-shrink-0">
                  <svg width="16" height="16" fill="none" stroke="#c9a84c" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-navy/40 uppercase tracking-widest mb-1">Dirección</p>
                  <p class="text-navy text-sm font-light">{{ $settings['church_address'] }}</p>
                  @if($settings['church_city'])
                    <p class="text-navy/60 text-sm font-light">{{ $settings['church_city'] }}</p>
                  @endif
                  @if($settings['church_maps_url'])
                    <a href="{{ $settings['church_maps_url'] }}" target="_blank"
                      class="text-gold text-xs hover:underline mt-1 inline-block">Abrir en Google Maps →</a>
                  @endif
                </div>
              </div>
            @endif

            @if($settings['church_phone'])
              <div class="flex gap-4 items-center bg-white border border-navy/10 p-5">
                <div class="w-10 h-10 rounded-full bg-navy flex items-center justify-center flex-shrink-0">
                  <svg width="16" height="16" fill="none" stroke="#c9a84c" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8 19.79 19.79 0 01.02 2.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-navy/40 uppercase tracking-widest mb-1">Teléfono</p>
                  <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['church_phone']) }}"
                    class="text-navy text-sm font-light hover:text-gold transition-colors">
                    {{ $settings['church_phone'] }}
                  </a>
                </div>
              </div>
            @endif
          </div>

          @if($settings['church_maps_embed'])
            <div class="overflow-hidden shadow-lg border border-navy/10">
              <iframe src="{{ $settings['church_maps_embed'] }}"
                width="100%" height="320" frameborder="0"
                style="border:0; display:block" allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          @endif

        </div>
      </div>
    @endif

  </div>
</section>
