<div class="space-y-6">

    {{-- ── Estado EN VIVO ── --}}
    @if($isLive)
    <div class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-xl">
        <span class="flex items-center gap-1.5 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-widest flex-shrink-0">
            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
            En vivo
        </span>
        <p class="text-sm text-red-700 flex-1">Hay una transmisión activa en este momento.</p>
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('live') }}" target="_blank"
                class="text-xs text-red-600 hover:text-red-800 font-medium underline">Ver página pública</a>
            <a href="{{ route('admin.settings') }}"
                class="text-xs text-red-500 hover:text-red-700">Desactivar</a>
        </div>
    </div>
    @endif

    {{-- ── Stat cards ── --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        <a href="{{ route('admin.sermons') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Sermones</span>
                <div class="w-9 h-9 rounded-lg bg-[#1a2e4a]/8 flex items-center justify-center group-hover:bg-[#1a2e4a]/15 transition-colors">
                    <svg class="w-4 h-4 text-[#1a2e4a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['sermons_published'] }}</p>
            <p class="text-xs text-gray-400 mt-1">sermones publicados</p>
        </a>

        <a href="{{ route('admin.events') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Próximos eventos</span>
                <div class="w-9 h-9 rounded-lg bg-purple-500/10 flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['events_upcoming'] }}</p>
            <p class="text-xs text-gray-400 mt-1">publicados y activos</p>
        </a>

        <a href="{{ route('admin.inscriptions') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Inscripciones</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <path d="M9 12h6M9 16h4"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['registrations_total'] }}</p>
            <p class="text-xs text-gray-400 mt-1">
                <span class="text-emerald-600 font-medium">{{ $stats['registrations_month'] }}</span>
                este mes · {{ $stats['attendees_total'] }} asistentes
            </p>
        </a>

        <a href="{{ route('admin.inscriptions') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Este mes</span>
                <div class="w-9 h-9 rounded-lg bg-[#c9a84c]/10 flex items-center justify-center group-hover:bg-[#c9a84c]/20 transition-colors">
                    <svg class="w-4 h-4 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['registrations_month'] }}</p>
            <p class="text-xs text-gray-400 mt-1">inscripciones en {{ now()->translatedFormat('F') }}</p>
        </a>

    </div>

    {{-- ── Fila central ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Inscripciones por evento --}}
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Inscripciones por evento</h2>
                <a href="{{ route('admin.inscriptions') }}" class="text-xs text-[#c9a84c] hover:underline">Ver todas</a>
            </div>

            @if($eventRegistrations->isEmpty())
                <p class="px-5 py-10 text-center text-gray-400 text-sm">Sin inscripciones aún.</p>
            @else
                <ul class="divide-y divide-gray-50">
                    @foreach($eventRegistrations as $event)
                    <li class="px-5 py-3.5">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('admin.inscriptions') }}"
                                    class="text-sm font-medium text-gray-700 hover:text-indigo-600 truncate block">
                                    {{ $event->title }}
                                </a>
                                <p class="text-xs text-gray-400">
                                    {{ $event->start_date->format('d M Y') }}
                                    @if($event->capacity)
                                        · cupo {{ $event->registrations_count }}/{{ $event->capacity }}
                                    @endif
                                </p>
                            </div>
                            <div class="ml-4 text-right flex-shrink-0">
                                <span class="text-sm font-semibold text-gray-800">{{ $event->registrations_count }}</span>
                                <span class="text-xs text-gray-400 ml-0.5">inscritos</span>
                                @if($event->registrations_sum_attendees)
                                    <p class="text-xs text-gray-400">{{ $event->registrations_sum_attendees }} asistentes</p>
                                @endif
                            </div>
                        </div>
                        {{-- Barra de progreso si tiene capacidad --}}
                        @if($event->capacity)
                            @php $pct = min(100, round($event->registrations_count / $event->capacity * 100)) @endphp
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                                <div class="h-1.5 rounded-full transition-all
                                    {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-400' : 'bg-emerald-500') }}"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Últimas inscripciones --}}
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Actividad reciente</h2>
                <a href="{{ route('admin.inscriptions') }}" class="text-xs text-[#c9a84c] hover:underline">Ver todas</a>
            </div>

            @if($recentRegistrations->isEmpty())
                <p class="px-5 py-10 text-center text-gray-400 text-sm">Sin actividad aún.</p>
            @else
                <ul class="divide-y divide-gray-50">
                    @foreach($recentRegistrations as $reg)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-indigo-600 text-xs font-semibold">
                                {{ strtoupper(substr($reg->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-700 truncate">{{ $reg->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $reg->event?->title ?? '—' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">
                                {{ $reg->attendees }}
                            </span>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $reg->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    {{-- ── Fila inferior ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Próximos eventos --}}
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Próximos eventos</h2>
                <a href="{{ route('admin.events') }}" class="text-xs text-[#c9a84c] hover:underline">Ver todos</a>
            </div>

            @if($upcomingEvents->isEmpty())
                <p class="px-5 py-10 text-center text-gray-400 text-sm">No hay eventos próximos.</p>
            @else
                <ul class="divide-y divide-gray-50">
                    @foreach($upcomingEvents as $event)
                    <li class="flex items-center gap-4 px-5 py-3.5">
                        {{-- Fecha --}}
                        <div class="flex-shrink-0 w-12 text-center">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wide leading-none">
                                {{ $event->start_date->translatedFormat('M') }}
                            </p>
                            <p class="text-2xl font-bold text-[#1a2e4a] leading-tight">
                                {{ $event->start_date->format('d') }}
                            </p>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-700 truncate">{{ $event->title }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $event->start_date->format('g:i A') }}
                                @if($event->location) · {{ $event->location }} @endif
                            </p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                {{ $event->registrations_count }} inscritos
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Sermones recientes --}}
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-700">Sermones recientes</h2>
                <a href="{{ route('admin.sermons') }}" class="text-xs text-[#c9a84c] hover:underline">Ver todos</a>
            </div>

            @if($recentSermons->isEmpty())
                <p class="px-5 py-10 text-center text-gray-400 text-sm">No hay sermones publicados.</p>
            @else
                <ul class="divide-y divide-gray-50">
                    @foreach($recentSermons as $sermon)
                    <li class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-lg bg-[#1a2e4a]/8 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#1a2e4a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-700 truncate">{{ $sermon->title }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $sermon->speaker }} · {{ $sermon->date->format('d M Y') }}
                            </p>
                        </div>
                        @if($sermon->audio_url || $sermon->video_url)
                            <span class="flex-shrink-0 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                {{ $sermon->video_url ? 'Video' : 'Audio' }}
                            </span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

</div>
