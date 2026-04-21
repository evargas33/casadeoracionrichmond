<div class="pt-24 pb-20 min-h-screen" style="background:#f5f0e8">

    {{-- Header --}}
    <section class="max-w-5xl mx-auto px-6 md:px-12 mb-10">
        <div class="gold-line">
            <h1 class="font-serif text-3xl md:text-4xl text-navy font-light tracking-wide">
                Área de Servidores
            </h1>
            <p class="text-navy/60 mt-2 text-sm font-light">
                Planificación de los próximos servicios · Solo visible para el equipo
            </p>
        </div>
    </section>

    {{-- Plans --}}
    <section class="max-w-5xl mx-auto px-6 md:px-12 space-y-6">

        @forelse ($plans as $plan)
            <div x-data="{ open: false }"
                class="bg-white rounded-2xl shadow-sm border border-navy/5 overflow-hidden card-hover fade-in">

                {{-- Card header --}}
                <button @click="open = !open"
                    class="w-full text-left px-6 py-5 flex items-center justify-between gap-4">

                    <div class="flex items-center gap-5">
                        {{-- Date badge --}}
                        <div class="flex-shrink-0 w-16 h-16 rounded-xl flex flex-col items-center justify-center"
                            style="background:#1a2e4a">
                            <span class="text-gold font-serif text-xl leading-none font-semibold">
                                {{ $plan->date->format('d') }}
                            </span>
                            <span class="text-white/60 text-xs uppercase tracking-wide mt-0.5">
                                {{ $plan->date->translatedFormat('M') }}
                            </span>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="font-serif text-xl text-navy font-light">{{ $plan->title }}</h2>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-navy/10 text-navy/60">
                                    {{ $plan->service_type_label }}
                                </span>
                            </div>
                            @if ($plan->sermon_topic)
                                <p class="text-navy/60 text-sm mt-0.5 italic">"{{ $plan->sermon_topic }}"</p>
                            @endif
                            <p class="text-navy/40 text-xs mt-1">
                                {{ $plan->date->translatedFormat('l, d \d\e F \d\e Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Completion pills --}}
                    <div class="hidden md:flex items-center gap-2 flex-shrink-0">
                        @php
                            $indicators = [
                                ['label' => 'Pred',  'done' => (bool) $plan->sermon_topic],
                                ['label' => 'Alab',  'done' => $plan->songs->count() > 0 || $plan->worship_uniform_color],
                                ['label' => 'Uj',    'done' => $plan->ushers->count() > 0],
                                ['label' => 'Tec',   'done' => $plan->technicians->count() > 0],
                            ];
                        @endphp
                        @foreach ($indicators as $ind)
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $ind['done'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ $ind['label'] }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Chevron --}}
                    <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-navy/30 transition-transform flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                {{-- Expanded content --}}
                <div x-show="open" x-collapse class="border-t border-navy/5">
                    <div class="grid md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-navy/5">

                        {{-- ── Predicación ── --}}
                        <div class="p-6 space-y-4">
                            <h3 class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-navy/40">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Predicación
                            </h3>

                            @if ($plan->sermon_topic)
                                <div>
                                    <p class="text-xs text-navy/40 mb-0.5">Tema</p>
                                    <p class="text-navy font-medium">{{ $plan->sermon_topic }}</p>
                                </div>
                            @endif
                            @if ($plan->bible_passage)
                                <div>
                                    <p class="text-xs text-navy/40 mb-0.5">Pasaje</p>
                                    <p class="text-navy">{{ $plan->bible_passage }}</p>
                                </div>
                            @endif

                            @if ($plan->sermon_notes_path || $plan->bible_citations_path)
                                <div class="space-y-2 pt-1">
                                    @if ($plan->sermon_notes_path)
                                        <a href="{{ asset('storage/' . $plan->sermon_notes_path) }}"
                                            target="_blank"
                                            class="flex items-center gap-2 text-xs text-navy/60 hover:text-navy border border-navy/10 rounded-lg px-3 py-2 hover:bg-navy/5 transition">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                                <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Notas del sermón
                                        </a>
                                    @endif
                                    @if ($plan->bible_citations_path)
                                        <a href="{{ asset('storage/' . $plan->bible_citations_path) }}"
                                            target="_blank"
                                            class="flex items-center gap-2 text-xs font-medium border rounded-lg px-3 py-2 transition"
                                            style="color:#a8892f;border-color:#c9a84c40;background:#fffbf0">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                                <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Citas para ProPresenter
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @if (!$plan->sermon_topic && !$plan->bible_passage && !$plan->sermon_notes_path)
                                <p class="text-navy/30 text-xs italic">Pendiente de información</p>
                            @endif
                        </div>

                        {{-- ── Alabanza ── --}}
                        <div class="p-6 space-y-4">
                            <h3 class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-navy/40">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                                </svg>
                                Alabanza
                            </h3>

                            @if ($plan->worship_uniform_color)
                                <div class="flex items-start gap-2">
                                    <span class="mt-0.5 w-3 h-3 rounded-full flex-shrink-0 border border-navy/20"
                                        style="background:{{ str_starts_with($plan->worship_uniform_color, '#') ? $plan->worship_uniform_color : 'transparent' }}"></span>
                                    <div>
                                        <p class="text-xs text-navy/40">Uniforme</p>
                                        <p class="text-navy text-sm">{{ $plan->worship_uniform_color }}</p>
                                        @if ($plan->worship_uniform_notes)
                                            <p class="text-navy/50 text-xs mt-0.5">{{ $plan->worship_uniform_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($plan->songs->count() > 0)
                                <div>
                                    <p class="text-xs text-navy/40 mb-2">Cantos ({{ $plan->songs->count() }})</p>
                                    <ol class="space-y-2">
                                        @foreach ($plan->songs as $song)
                                            <li class="flex items-start gap-3">
                                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-navy/10 text-navy/50 text-xs flex items-center justify-center font-medium">
                                                    {{ $loop->iteration }}
                                                </span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-navy text-sm font-medium leading-tight">{{ $song->title }}</p>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        @if ($song->artist)
                                                            <p class="text-navy/40 text-xs">{{ $song->artist }}</p>
                                                        @endif
                                                        @if ($song->song_key)
                                                            <span class="text-xs px-1.5 py-0 rounded bg-navy/10 text-navy/60 font-mono">{{ $song->song_key }}</span>
                                                        @endif
                                                    </div>
                                                    @if ($song->notes)
                                                        <p class="text-navy/40 text-xs italic">{{ $song->notes }}</p>
                                                    @endif
                                                    <div class="flex gap-2 mt-1">
                                                        @if ($song->onsong_path)
                                                            <a href="{{ asset('storage/' . $song->onsong_path) }}" target="_blank"
                                                                class="text-xs text-indigo-500 hover:underline">↓ OnSong</a>
                                                        @endif
                                                        @if ($song->pdf_path)
                                                            <a href="{{ asset('storage/' . $song->pdf_path) }}" target="_blank"
                                                                class="text-xs text-red-500 hover:underline">↓ PDF</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif

                            @if (!$plan->worship_uniform_color && $plan->songs->count() === 0)
                                <p class="text-navy/30 text-xs italic">Pendiente de información</p>
                            @endif
                        </div>

                        {{-- ── Ujieres ── --}}
                        <div class="p-6 space-y-4">
                            <h3 class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-navy/40">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Ujieres
                            </h3>

                            @if ($plan->usher_uniform_color)
                                <div>
                                    <p class="text-xs text-navy/40">Uniforme</p>
                                    <p class="text-navy text-sm">{{ $plan->usher_uniform_color }}</p>
                                    @if ($plan->usher_uniform_notes)
                                        <p class="text-navy/50 text-xs mt-0.5">{{ $plan->usher_uniform_notes }}</p>
                                    @endif
                                </div>
                            @endif

                            @if ($plan->ushers->count() > 0)
                                @php
                                    $grouped = $plan->ushers->groupBy('assignment');
                                    $labels = ['entrada' => 'Entrada', 'ofrendas' => 'Ofrendas', 'general' => 'General', 'apoyo' => 'Apoyo'];
                                @endphp
                                <div class="space-y-3">
                                    @foreach ($grouped as $assignment => $group)
                                        <div>
                                            <p class="text-xs text-navy/40 font-medium mb-1">{{ $labels[$assignment] ?? $assignment }}</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($group as $usher)
                                                    <span class="text-xs px-2.5 py-1 rounded-full bg-navy/8 text-navy border border-navy/10">
                                                        {{ $usher->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if (!$plan->usher_uniform_color && $plan->ushers->count() === 0)
                                <p class="text-navy/30 text-xs italic">Pendiente de información</p>
                            @endif
                        </div>

                        {{-- ── Técnicos ── --}}
                        <div class="p-6 space-y-4">
                            <h3 class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-navy/40">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                                </svg>
                                Equipo Técnico
                            </h3>

                            @if ($plan->technicians->count() > 0)
                                @php
                                    $posLabels = [
                                        'mixer'      => ['label' => 'Mixer / Sonido',    'icon' => '🎚️'],
                                        'proyeccion' => ['label' => 'Proyección',         'icon' => '📺'],
                                        'streaming'  => ['label' => 'Streaming',          'icon' => '📡'],
                                        'apoyo'      => ['label' => 'Apoyo técnico',      'icon' => '🔧'],
                                    ];
                                @endphp
                                <div class="space-y-2">
                                    @foreach ($plan->technicians as $tech)
                                        @php $pos = $posLabels[$tech->position] ?? ['label' => $tech->position, 'icon' => '⚙️']; @endphp
                                        <div class="flex items-center gap-3">
                                            <span class="text-base w-6 text-center">{{ $pos['icon'] }}</span>
                                            <div>
                                                <p class="text-navy text-sm font-medium leading-tight">{{ $tech->name }}</p>
                                                <p class="text-navy/40 text-xs">{{ $pos['label'] }}</p>
                                                @if ($tech->notes)
                                                    <p class="text-navy/40 text-xs italic">{{ $tech->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-navy/30 text-xs italic">Pendiente de asignación</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <p class="text-navy/40 font-serif text-xl font-light">No hay servicios publicados próximamente.</p>
                <p class="text-navy/30 text-sm mt-2">Vuelve más tarde para ver la planificación.</p>
            </div>
        @endforelse

    </section>

</div>

@push('styles')
<style>
    .gold-line::before { content:''; display:block; width:56px; height:2px; background:#c9a84c; margin-bottom:1rem; }
    .fade-in { opacity: 0; transform: translateY(16px); transition: opacity .5s ease, transform .5s ease; }
    .fade-in.visible { opacity: 1; transform: translateY(0); }
    .card-hover { transition: box-shadow .3s ease; }
    .card-hover:hover { box-shadow: 0 8px 32px rgba(26,46,74,.10); }
    [x-cloak] { display: none !important; }
</style>
@endpush
