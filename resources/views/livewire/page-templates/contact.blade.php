{{-- Template: contact — Página de contacto --}}

{{-- Hero --}}
<section class="pt-32 pb-14" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 100%);">
  <div class="max-w-4xl mx-auto px-6 md:px-12 text-center">
    <p class="text-gold/80 text-xs tracking-[.35em] uppercase font-sans mb-4">Contacto</p>
    <h1 class="font-serif font-light text-4xl md:text-5xl text-white leading-tight">{{ $page->title }}</h1>
  </div>
</section>

<section class="py-16 bg-cream">
  <div class="max-w-5xl mx-auto px-6 md:px-12">

    <div class="grid lg:grid-cols-2 gap-12">

      {{-- Contenido + tarjetas de contacto --}}
      <div class="space-y-8">

        @if($page->content)
          <div class="prose max-w-none font-light leading-relaxed text-navy/80 page-content">
            {!! $page->content !!}
          </div>
        @endif

        {{-- Tarjetas de contacto --}}
        <div class="space-y-4">

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
                    class="text-gold text-xs hover:underline mt-1 inline-block">Ver en Google Maps →</a>
                @endif
              </div>
            </div>
          @endif

          @if($settings['church_phone'])
            <div class="flex gap-4 items-start bg-white border border-navy/10 p-5">
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

          @if($settings['church_email'])
            <div class="flex gap-4 items-start bg-white border border-navy/10 p-5">
              <div class="w-10 h-10 rounded-full bg-navy flex items-center justify-center flex-shrink-0">
                <svg width="16" height="16" fill="none" stroke="#c9a84c" stroke-width="1.5" viewBox="0 0 24 24">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                  <polyline points="22,6 12,13 2,6"/>
                </svg>
              </div>
              <div>
                <p class="text-xs text-navy/40 uppercase tracking-widest mb-1">Correo</p>
                <a href="mailto:{{ $settings['church_email'] }}"
                  class="text-navy text-sm font-light hover:text-gold transition-colors">
                  {{ $settings['church_email'] }}
                </a>
              </div>
            </div>
          @endif

          {{-- Redes sociales --}}
          @if($settings['social_facebook'] || $settings['social_instagram'] || $settings['social_youtube'])
            <div class="flex gap-4 items-center bg-white border border-navy/10 p-5">
              <div class="w-10 h-10 rounded-full bg-navy flex items-center justify-center flex-shrink-0">
                <svg width="16" height="16" fill="none" stroke="#c9a84c" stroke-width="1.5" viewBox="0 0 24 24">
                  <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                  <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                  <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
              </div>
              <div>
                <p class="text-xs text-navy/40 uppercase tracking-widest mb-2">Redes sociales</p>
                <div class="flex gap-3">
                  @if($settings['social_facebook'])
                    <a href="{{ $settings['social_facebook'] }}" target="_blank" class="text-navy/40 hover:text-gold transition-colors">
                      <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                  @endif
                  @if($settings['social_instagram'])
                    <a href="{{ $settings['social_instagram'] }}" target="_blank" class="text-navy/40 hover:text-gold transition-colors">
                      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                    </a>
                  @endif
                  @if($settings['social_youtube'])
                    <a href="{{ $settings['social_youtube'] }}" target="_blank" class="text-navy/40 hover:text-gold transition-colors">
                      <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#f5f0e8"/></svg>
                    </a>
                  @endif
                </div>
              </div>
            </div>
          @endif

        </div>
      </div>

      {{-- Mapa --}}
      @if($settings['church_maps_embed'])
        <div class="space-y-4">
          <h2 class="font-serif text-navy text-2xl font-light gold-line">Cómo llegar</h2>
          <div class="overflow-hidden shadow-lg border border-navy/10">
            <iframe src="{{ $settings['church_maps_embed'] }}"
              width="100%" height="400" frameborder="0"
              style="border:0; display:block" allowfullscreen loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      @endif

    </div>
  </div>
</section>
