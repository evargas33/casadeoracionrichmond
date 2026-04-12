<div>
@if($isLive && $videoId)
{{-- ══════════════════════════════════════════
     EN VIVO
══════════════════════════════════════════ --}}
<section class="min-h-screen pt-20 pb-16" style="background:#0f1c2e">
    <div class="max-w-5xl mx-auto px-4 md:px-8">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6 pt-8">
            <span class="flex items-center gap-1.5 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                En vivo
            </span>
            <p class="text-white/50 text-sm">Transmisión en directo</p>
        </div>

        {{-- Player --}}
        <div class="relative w-full rounded-2xl overflow-hidden shadow-2xl" style="padding-bottom: 56.25%">
            <iframe
                src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&rel=0&modestbranding=1"
                title="Transmisión en vivo"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                allowfullscreen
                class="absolute inset-0 w-full h-full border-0"
            ></iframe>
        </div>

        {{-- Chat link --}}
        <div class="mt-4 flex items-center justify-end">
            <a href="https://youtube.com/watch?v={{ $videoId }}"
               target="_blank"
               class="flex items-center gap-1.5 text-xs text-white/40 hover:text-white/70 transition-colors">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/>
                    <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#0f1c2e"/>
                </svg>
                Ver en YouTube &amp; participar en el chat
            </a>
        </div>

    </div>
</section>

@else
{{-- ══════════════════════════════════════════
     SIN TRANSMISIÓN ACTIVA
══════════════════════════════════════════ --}}
<section class="min-h-screen pt-20 pb-16" style="background:#0f1c2e">
    <div class="max-w-3xl mx-auto px-4 md:px-8 text-center pt-20">

        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6" style="background:rgba(201,168,76,.1); border:1px solid rgba(201,168,76,.2)">
            <svg class="w-8 h-8 text-gold-400" fill="none" stroke="#c9a84c" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>

        <h1 class="font-serif text-white text-3xl md:text-4xl mb-4" style="font-weight:300">
            No hay transmisión activa
        </h1>
        <p class="text-white/50 text-sm mb-10 leading-relaxed max-w-md mx-auto">
            En este momento no estamos transmitiendo en vivo.<br>
            Mientras tanto, puedes escuchar nuestros sermones más recientes.
        </p>

        @if($sermons->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left mb-10">
                @foreach($sermons as $sermon)
                    <a href="{{ route('sermons.show', $sermon->slug) }}"
                       class="flex gap-4 p-4 rounded-xl transition-colors group"
                       style="background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06)">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:rgba(201,168,76,.12)">
                            <svg class="w-5 h-5" fill="none" stroke="#c9a84c" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-white text-sm font-medium truncate group-hover:text-[#c9a84c] transition-colors">{{ $sermon->title }}</p>
                            <p class="text-white/40 text-xs mt-0.5">{{ $sermon->speaker }} · {{ $sermon->date->format('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <a href="{{ route('sermons.index') }}"
           class="inline-flex items-center gap-2 border text-sm px-6 py-2.5 transition-all"
           style="border-color:rgba(201,168,76,.5); color:#c9a84c"
           onmouseover="this.style.background='#c9a84c';this.style.color='#0f1c2e'"
           onmouseout="this.style.background='';this.style.color='#c9a84c'">
            Ver todos los sermones
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>

    </div>
</section>
@endif
</div>
