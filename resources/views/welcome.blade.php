@php
use App\Models\Setting;
$churchName     = Setting::get('church_name',        'Casa de Oración');
$churchTagline  = Setting::get('church_tagline',     'Iglesia Evangélica · Bay Area');
$churchDesc     = Setting::get('church_description', 'Comunidad hispana de fe, adoración y servicio. Te esperamos con los brazos abiertos.');
$churchFounded  = Setting::get('church_founded',     '2009');
$pastorName     = Setting::get('pastor_name',        'Pastor José Hernández');
$pastorTitle    = Setting::get('pastor_title',       'Pastor Principal');
$churchAddress  = Setting::get('church_address',     '1245 Mission Blvd, Suite 4');
$churchCity     = Setting::get('church_city',        'San Francisco, CA 94110');
$churchPhone    = Setting::get('church_phone',       '(415) 555-0192');
$churchEmail    = Setting::get('church_email',       'info@casadeoracion.org');
$churchMapsUrl   = Setting::get('church_maps_url',    'https://maps.google.com');
$churchMapsEmbed = Setting::get('church_maps_embed',  '');
$scheduleSun    = Setting::get('schedule_sunday',    '10:00 am');
$scheduleSat    = Setting::get('schedule_saturday',  '7:00 pm');
$scheduleFri    = Setting::get('schedule_friday',    '7:30 pm');
$socialFb       = Setting::get('social_facebook',    '');
$socialIg       = Setting::get('social_instagram',   '');
$socialYt       = Setting::get('social_youtube',     '');
$metaTitle      = Setting::get('meta_title',         'Casa de Oración — Iglesia Evangélica');
$metaDesc       = Setting::get('meta_description',   'Iglesia evangélica hispana en el Área de la Bahía.');
$isLive         = (bool) Setting::get('live_stream_active', false)
                  && Setting::get('live_stream_video_id', '') !== '';
$menuPages      = \App\Models\Page::inMenu()->get();
$upcomingEvents = \App\Models\Event::published()->where('start_date', '>=', now())->orderBy('start_date')->limit(3)->get();
$recentSermons  = \App\Models\Sermon::with('series')->published()->orderByDesc('date')->limit(3)->get();
$featuredSeries = \App\Models\Serie::where('active', true)
    ->whereHas('sermons', fn($q) => $q->where('published', true))
    ->withCount(['sermons' => fn($q) => $q->where('published', true)])
    ->orderByDesc('order')
    ->first();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $metaDesc }}">
<title>{{ $metaTitle }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          navy:  { DEFAULT: '#1a2e4a', light: '#243d61', dark: '#111e30' },
          gold:  { DEFAULT: '#c9a84c', light: '#ddbf73', dark: '#a8892f' },
          cream: { DEFAULT: '#f5f0e8', dark: '#e8e0d0' },
        },
        fontFamily: {
          serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
          sans:  ['"DM Sans"', 'sans-serif'],
        },
      }
    }
  }
</script>
<style>
  * { scroll-behavior: smooth; }
  body { font-family: 'DM Sans', sans-serif; background: #f5f0e8; color: #1a2e4a; }

  #nav { transition: background .4s, box-shadow .4s; }
  #nav.scrolled { background: rgba(26,46,74,0.97); box-shadow: 0 2px 24px rgba(0,0,0,.25); }

  .hero-overlay { background: linear-gradient(to bottom, rgba(17,30,48,.72) 0%, rgba(17,30,48,.45) 50%, rgba(17,30,48,.85) 100%); }

  .gold-line::before { content:''; display:block; width:56px; height:2px; background:#c9a84c; margin-bottom:1rem; }

  .card-hover { transition: transform .3s ease, box-shadow .3s ease; }
  .card-hover:hover { transform: translateY(-6px); box-shadow: 0 24px 48px rgba(26,46,74,.15); }

  .play-btn { transition: all .25s; }
  .sermon-card:hover .play-btn { transform: scale(1.12); background: #c9a84c; }

  .fade-in { opacity: 0; transform: translateY(28px); transition: opacity .7s ease, transform .7s ease; }
  .fade-in.visible { opacity: 1; transform: translateY(0); }

  .gold-divider { width: 100%; height: 1px; background: linear-gradient(to right, transparent, #c9a84c55, transparent); }

  @keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(8px)} }
  .scroll-bounce { animation: bounce 2s ease-in-out infinite; }

  @keyframes reveal { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
  .hero-title    { animation: reveal .9s ease .2s both; }
  .hero-sub      { animation: reveal .9s ease .5s both; }
  .hero-ctas     { animation: reveal .9s ease .8s both; }
  .hero-schedule { animation: reveal .9s ease 1.0s both; }

  .nav-link { position:relative; }
  .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:1px; background:#c9a84c; transition:width .3s; }
  .nav-link:hover::after { width:100%; }

  blockquote { border-left: 3px solid #c9a84c; }
</style>
</head>
<body>

<!-- NAV -->
<nav id="nav" class="fixed top-0 w-full z-50 py-4 px-6 md:px-12">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <a href="#inicio" class="flex items-center gap-3">
      <img src="{{ asset('images/download.jpeg') }}" alt="{{ $churchName }}" class="w-9 h-9 rounded-full object-cover border border-gold/40">
      <span class="font-serif text-white text-lg tracking-wide">{{ $churchName }}</span>
    </a>

    <div class="hidden md:flex items-center gap-8">
      @if($isLive)
        <a href="{{ route('live') }}" class="flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-widest transition-colors">
          <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
          En vivo
        </a>
      @endif
      <a href="#acerca"    class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Acerca</a>
      <a href="#eventos"   class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Eventos</a>
      <a href="#sermones"  class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Sermones</a>
      <a href="#visitanos" class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Visítanos</a>
      @foreach($menuPages as $menuPage)
        <a href="{{ route('page.show', $menuPage->slug) }}"
           class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">
          {{ $menuPage->title }}
        </a>
      @endforeach
      @auth
        @if(auth()->user()->hasAnyRole(['superadmin','admin','servidor','pastor','lider_alabanza','lider_ujieres','lider_tecnicos']))
          <a href="{{ route('servidores') }}" class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Servidores</a>
        @endif
        @if(auth()->user()->hasAnyRole(['superadmin','admin','editor','member']))
          <a href="{{ route('admin.dashboard') }}" class="border border-gold text-gold px-5 py-1.5 text-sm font-light tracking-wide hover:bg-gold hover:text-navy transition-all duration-300">Panel</a>
        @elseif(auth()->user()->hasAnyRole(['pastor','lider_alabanza','lider_ujieres','lider_tecnicos']))
          <a href="{{ route('admin.services') }}" class="border border-gold text-gold px-5 py-1.5 text-sm font-light tracking-wide hover:bg-gold hover:text-navy transition-all duration-300">Mis Servicios</a>
        @endif
      @else
        <a href="{{ route('login') }}" class="border border-gold text-gold px-5 py-1.5 text-sm font-light tracking-wide hover:bg-gold hover:text-navy transition-all duration-300">Iniciar sesión</a>
      @endauth
    </div>

    <button id="menu-btn" class="md:hidden text-white" onclick="toggleMenu()">
      <svg id="icon-open"  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="7" x2="21" y2="7"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="17" x2="21" y2="17"/></svg>
      <svg id="icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="hidden"><line x1="4" y1="4" x2="20" y2="20"/><line x1="20" y1="4" x2="4" y2="20"/></svg>
    </button>
  </div>

  <div id="mobile-menu" class="hidden mt-4 pb-4 border-t border-white/10 flex-col gap-4 pt-4">
    @if($isLive)
      <a href="{{ route('live') }}" class="flex items-center gap-1.5 text-red-400 text-sm tracking-wide font-semibold">
        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
        En vivo
      </a>
    @endif
    <a href="#acerca"    onclick="closeMenu()" class="text-white/80 text-sm tracking-wide">Acerca</a>
    <a href="#eventos"   onclick="closeMenu()" class="text-white/80 text-sm tracking-wide">Eventos</a>
    <a href="#sermones"  onclick="closeMenu()" class="text-white/80 text-sm tracking-wide">Sermones</a>
    <a href="#visitanos" onclick="closeMenu()" class="text-white/80 text-sm tracking-wide">Visítanos</a>
    @foreach($menuPages as $menuPage)
      <a href="{{ route('page.show', $menuPage->slug) }}" onclick="closeMenu()" class="text-white/80 text-sm tracking-wide">
        {{ $menuPage->title }}
      </a>
    @endforeach
    @auth
      @if(auth()->user()->hasAnyRole(['superadmin','admin','servidor','pastor','lider_alabanza','lider_ujieres','lider_tecnicos']))
        <a href="{{ route('servidores') }}" onclick="closeMenu()" class="text-white/80 text-sm tracking-wide">Servidores</a>
      @endif
      @if(auth()->user()->hasAnyRole(['superadmin','admin','editor','member']))
        <a href="{{ route('admin.dashboard') }}" onclick="closeMenu()" class="text-gold text-sm tracking-wide">Panel de administración</a>
      @elseif(auth()->user()->hasAnyRole(['pastor','lider_alabanza','lider_ujieres','lider_tecnicos']))
        <a href="{{ route('admin.services') }}" onclick="closeMenu()" class="text-gold text-sm tracking-wide">Mis Servicios</a>
      @endif
    @else
      <a href="{{ route('login') }}" onclick="closeMenu()" class="text-gold text-sm tracking-wide">Iniciar sesión</a>
    @endauth
  </div>
</nav>

@if($isLive)
<!-- LIVE BANNER -->
<div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40">
  <a href="{{ route('live') }}"
     class="flex items-center gap-3 bg-[#0f1c2e] border border-red-500/40 text-white px-5 py-3 rounded-full shadow-2xl hover:border-red-500 transition-all group">
    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-red-600 flex-shrink-0">
      <span class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
    </span>
    <span class="text-sm font-medium">Estamos transmitiendo en vivo ahora</span>
    <svg class="w-4 h-4 text-white/50 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
    </svg>
  </a>
</div>
@endif

<!-- HERO -->
<section id="inicio" class="relative h-screen min-h-[640px] flex items-center justify-center overflow-hidden">
  <img src="https://picsum.photos/seed/worship/1600/900" alt="Iglesia" class="absolute inset-0 w-full h-full object-cover object-center">
  <div class="hero-overlay absolute inset-0"></div>

  <div class="relative z-10 text-center text-white px-6 max-w-4xl mx-auto">
    <p class="hero-sub font-sans text-gold/90 text-xs tracking-[.35em] uppercase mb-6">{{ $churchTagline }}</p>
    <h1 class="hero-title font-serif font-light text-5xl md:text-7xl lg:text-8xl leading-none mb-6">
      Un lugar donde<br><em class="italic text-gold">la fe</em> cobra vida
    </h1>
    <p class="hero-sub font-sans font-light text-white/70 text-base md:text-lg max-w-xl mx-auto mb-10">
      {{ $churchDesc }}
    </p>
    <div class="hero-ctas flex flex-col sm:flex-row gap-4 justify-center items-center">
      <a href="#acerca"   class="bg-gold text-navy px-8 py-3 text-sm font-medium tracking-wide hover:bg-yellow-400 transition-colors duration-300">Conócenos</a>
      <a href="#sermones" class="border border-white/50 text-white px-8 py-3 text-sm font-light tracking-wide hover:border-gold hover:text-gold transition-colors duration-300">Ver sermones</a>
    </div>

    <div class="hero-schedule mt-16 grid grid-cols-3 gap-px bg-white/10 max-w-xl mx-auto text-center">
      <div class="bg-navy/40 backdrop-blur-sm px-4 py-4">
        <p class="text-gold text-xs tracking-widest uppercase mb-1">Domingo</p>
        <p class="text-white font-serif text-xl font-light">{{ $scheduleSun }}</p>
      </div>
      <div class="bg-navy/40 backdrop-blur-sm px-4 py-4">
        <p class="text-gold text-xs tracking-widest uppercase mb-1">Sábado</p>
        <p class="text-white font-serif text-xl font-light">{{ $scheduleSat }}</p>
      </div>
      <div class="bg-navy/40 backdrop-blur-sm px-4 py-4">
        <p class="text-gold text-xs tracking-widest uppercase mb-1">Viernes</p>
        <p class="text-white font-serif text-xl font-light">{{ $scheduleFri }}</p>
      </div>
    </div>
  </div>

  <div class="scroll-bounce absolute bottom-8 left-1/2 -translate-x-1/2 text-white/40">
    <svg width="20" height="32" viewBox="0 0 20 32" fill="none" stroke="currentColor" stroke-width="1"><rect x="1" y="1" width="18" height="30" rx="9"/><circle cx="10" cy="8" r="2.5" fill="currentColor" stroke="none"><animate attributeName="cy" values="8;20;8" dur="2s" repeatCount="indefinite"/><animate attributeName="opacity" values="1;0;1" dur="2s" repeatCount="indefinite"/></circle></svg>
  </div>
</section>

<div class="gold-divider"></div>

<!-- ACERCA -->
<section id="acerca" class="py-24 md:py-32 bg-cream">
  <div class="max-w-7xl mx-auto px-6 md:px-12">
    <div class="grid md:grid-cols-2 gap-16 md:gap-24 items-center">

      <div class="relative fade-in">
        <img src="https://images.squarespace-cdn.com/content/v1/5963c336bf629a58762b0977/1727128124713-L8XK4PNE9VC5Q2SKUHP8/DSC_4076.jpg" alt="Congregación" class="w-full aspect-[4/5] object-cover">
        <div class="absolute -bottom-6 -right-6 w-40 h-40 bg-navy hidden md:flex items-center justify-center text-center p-4">
          <div>
            <p class="font-serif text-gold text-3xl font-light">{{ date('Y') - (int)$churchFounded }}+</p>
            <p class="text-white/70 text-xs font-light tracking-widest uppercase mt-1">Años sirviendo</p>
          </div>
        </div>
      </div>

      <div class="fade-in">
        <p class="gold-line text-gold text-xs tracking-[.3em] uppercase font-sans">Nuestra historia</p>
        <h2 class="font-serif font-light text-4xl md:text-5xl leading-tight mb-6 text-navy">
          Más que una iglesia,<br><em class="italic text-gold">una familia</em>
        </h2>
        <p class="text-navy/70 font-light text-base leading-relaxed mb-6">
          Desde 2015, Casa de Oración Richmond ha sido un refugio espiritual para la comunidad hispana en el área de la Bahía. Creemos en un evangelio sano que transforma vidas, familias y comunidades enteras.
        </p>
        <blockquote class="pl-5 py-1 mb-8 font-serif italic text-navy/60 text-lg">
          "yo los llevaré a mi santo monte, y los recrearé en mi casa de oración; sus holocaustos y sus sacrificios serán aceptos sobre mi altar; porque mi casa será llamada casa de oración para todos los pueblos."<br>
          <cite class="text-gold not-italic text-sm font-sans font-light">— Isaías 56:7 </cite>
        </blockquote>

        <div class="grid grid-cols-2 gap-6 mb-10">
          @foreach(['Fe'=>'Fundamentada en la Palabra','Comunidad'=>'Familia que cuida y crece','Servicio'=>'Siervos del reino de Dios','Misión'=>'Alcanzando las naciones'] as $titulo=>$desc)
          <div class="border-l-2 border-gold pl-4">
            <p class="font-serif text-2xl text-navy font-light mb-1">{{ $titulo }}</p>
            <p class="text-navy/60 text-sm font-light">{{ $desc }}</p>
          </div>
          @endforeach
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-navy/10">
          <img src="https://picsum.photos/seed/pastor/120/120" alt="Pastor" class="w-14 h-14 rounded-full object-cover ring-2 ring-gold/30">
          <div>
            <p class="font-serif text-navy text-lg font-light">{{ $pastorName }}</p>
            <p class="text-navy/50 text-sm font-light">{{ $pastorTitle }} · {{ $churchName }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="gold-divider"></div>

<!-- EVENTOS -->
<section id="eventos" class="py-24 md:py-32 bg-navy">
  <div class="max-w-7xl mx-auto px-6 md:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6 fade-in">
      <div>
        <p class="gold-line text-gold text-xs tracking-[.3em] uppercase font-sans">Agenda</p>
        <h2 class="font-serif font-light text-4xl md:text-5xl text-white leading-tight">
          Próximos <em class="italic text-gold">eventos</em>
        </h2>
      </div>
      <a href="{{ route('events.index') }}" class="self-start md:self-auto border border-white/20 text-white/70 px-6 py-2.5 text-sm font-light tracking-wide hover:border-gold hover:text-gold transition-colors duration-300">Ver todos →</a>
    </div>

    @if($upcomingEvents->isNotEmpty())
    <div class="grid md:grid-cols-3 gap-6">
      @foreach($upcomingEvents as $e)
      @php
        $daysLeft = now()->diffInDays($e->start_date, false);
        if ($e->featured) {
            $badgeText = 'Destacado'; $badgeClass = 'bg-gold text-navy';
        } elseif ($daysLeft <= 7) {
            $badgeText = 'Esta semana'; $badgeClass = 'bg-gold text-navy';
        } elseif ($daysLeft <= 31) {
            $badgeText = 'Este mes'; $badgeClass = 'bg-white/10 border border-white/20 text-white';
        } else {
            $badgeText = $e->start_date->translatedFormat('F'); $badgeClass = 'bg-white/10 border border-white/20 text-white';
        }
      @endphp
      <article class="card-hover border border-white/10 overflow-hidden fade-in group" style="background:#243d61">
        <a href="{{ route('events.show', $e->slug) }}" class="block relative overflow-hidden aspect-[16/9]">
          @if($e->image)
            <img src="{{ $e->image }}" alt="{{ $e->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
            @if($e->all_day)
              {{ $e->start_date->translatedFormat('j \d\e F') }}
              @if($e->end_date && $e->end_date->format('Y-m-d') !== $e->start_date->format('Y-m-d'))
                – {{ $e->end_date->translatedFormat('j \d\e F') }}
              @endif
            @else
              {{ $e->start_date->translatedFormat('j \d\e F · g:i a') }}
            @endif
          </div>
          <h3 class="font-serif text-white text-xl font-light mb-2">{{ $e->title }}</h3>
          <p class="text-white/50 text-sm font-light leading-relaxed mb-5 line-clamp-2">{{ $e->short_description ?: $e->description }}</p>
          <div class="flex items-center justify-between">
            @if($e->location)
              <span class="flex items-center gap-1.5 text-white/40 text-xs">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $e->location }}
              </span>
            @else
              <span></span>
            @endif
            <a href="{{ route('events.show', $e->slug) }}" class="text-gold text-xs tracking-wide hover:underline">Ver más →</a>
          </div>
        </div>
      </article>
      @endforeach
    </div>
    @else
    <p class="text-white/30 text-center font-serif text-lg font-light py-12">No hay eventos próximos por el momento.</p>
    @endif
  </div>
</section>

<div class="gold-divider"></div>

<!-- SERMONES -->
<section id="sermones" class="py-24 md:py-32 bg-cream">
  <div class="max-w-7xl mx-auto px-6 md:px-12">
    <div class="text-center mb-14 fade-in">
      <p class="gold-line text-gold text-xs tracking-[.3em] uppercase font-sans inline-flex flex-col items-center">Palabra de vida</p>
      <h2 class="font-serif font-light text-4xl md:text-5xl text-navy leading-tight mb-4">
        Sermones <em class="italic text-gold">recientes</em>
      </h2>
      <p class="text-navy/60 font-light max-w-xl mx-auto text-base">Escucha y comparte la Palabra predicada cada semana en nuestra congregación.</p>
    </div>

    @if($featuredSeries)
    @php
      $latestInSeries = $featuredSeries->sermons()->where('published', true)->orderByDesc('date')->first();
      $seriesImage = ($featuredSeries->image && !str_starts_with($featuredSeries->image, 'http'))
          ? asset('storage/' . $featuredSeries->image)
          : null;
      $featuredSeriesThumb = $seriesImage ?? ($latestInSeries ? $latestInSeries->thumbnail : null);
    @endphp
    <div class="grid md:grid-cols-2 gap-0 mb-10 fade-in overflow-hidden">
      <div class="relative">
        @if($featuredSeriesThumb)
          <img src="{{ $featuredSeriesThumb }}" alt="{{ $featuredSeries->title }}" class="w-full h-full min-h-[300px] object-cover">
        @else
          <div class="w-full min-h-[300px] flex items-center justify-center" style="background:linear-gradient(135deg,#243d61,#1a2e4a)">
            <svg class="w-16 h-16 text-gold/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          </div>
        @endif
      </div>
      <div class="bg-navy p-10 flex flex-col justify-center">
        <span class="text-gold text-xs tracking-[.3em] uppercase font-sans mb-4">Serie destacada</span>
        <h3 class="font-serif text-white text-3xl font-light leading-snug mb-4">{{ $featuredSeries->title }}</h3>
        @if($featuredSeries->description)
          <p class="text-white/60 font-light text-sm leading-relaxed mb-8">{{ $featuredSeries->description }}</p>
        @else
          <div class="mb-8"></div>
        @endif
        <div class="flex items-center gap-4">
          <a href="{{ route('sermons.index', ['filterSeries' => $featuredSeries->id]) }}"
            class="flex items-center gap-3 bg-gold text-navy px-6 py-3 text-sm font-medium hover:bg-yellow-400 transition-colors">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>
            Escuchar serie
          </a>
          <span class="text-white/40 text-sm font-light">
            {{ $featuredSeries->sermons_count }} {{ $featuredSeries->sermons_count === 1 ? 'mensaje' : 'mensajes' }}
          </span>
        </div>
      </div>
    </div>
    @endif

    @if($recentSermons->isNotEmpty())
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
      @foreach($recentSermons as $s)
      <article class="sermon-card card-hover bg-white overflow-hidden fade-in group" style="border:1px solid rgba(26,46,74,.08)">
        <a href="{{ route('sermons.show', $s->slug) }}" class="block relative overflow-hidden aspect-video">
          @if($s->thumbnail)
            <img src="{{ $s->thumbnail }}" alt="{{ $s->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          @else
            <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#1a2e4a,#243d61)">
              <svg class="w-10 h-10 text-gold/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
            </div>
          @endif
          <div class="absolute inset-0 bg-navy/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="play-btn w-12 h-12 rounded-full bg-gold/90 flex items-center justify-center">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="white"><polygon points="5,3 19,12 5,21"/></svg>
            </div>
          </div>
          @if($s->duration_minutes)
            <div class="absolute bottom-2 right-2 bg-navy/80 text-white text-xs px-2 py-0.5 font-light">{{ $s->duration_minutes }} min</div>
          @endif
        </a>
        <div class="p-5">
          <span class="text-gold text-xs tracking-widest uppercase font-sans">{{ $s->series?->title ?? 'Especial' }}</span>
          <h4 class="font-serif text-navy text-lg font-light mt-1 mb-1">{{ $s->title }}</h4>
          <p class="text-navy/50 text-xs font-light mb-3">{{ $s->speaker }} · {{ $s->date->translatedFormat('j M Y') }}</p>
          <a href="{{ route('sermons.show', $s->slug) }}" class="text-gold text-xs tracking-wide hover:underline">Ver más →</a>
        </div>
      </article>
      @endforeach
    </div>
    @endif

    <div class="text-center mt-10 fade-in">
      <a href="{{ route('sermons.index') }}" class="border border-navy/30 text-navy px-8 py-3 text-sm font-light tracking-wide hover:border-navy hover:bg-navy hover:text-white transition-all duration-300 inline-block">Ver todos los sermones</a>
    </div>
  </div>
</section>

<div class="gold-divider"></div>

<!-- BANNER -->
<section class="relative py-24 overflow-hidden">
  <img src="https://picsum.photos/seed/banner/1600/900" alt="Comunidad" class="absolute inset-0 w-full h-full object-cover">
  <div class="absolute inset-0 bg-navy/80"></div>
  <div class="relative z-10 max-w-4xl mx-auto px-6 text-center fade-in">
    <p class="text-gold/80 text-xs tracking-[.35em] uppercase font-sans mb-4">¿Primera vez?</p>
    <h2 class="font-serif font-light text-4xl md:text-5xl text-white leading-tight mb-6">
      No tienes que venir <em class="italic text-gold">solo</em>
    </h2>
    <p class="text-white/60 font-light text-base max-w-xl mx-auto mb-10">
      Sabemos que visitar una iglesia nueva puede ser intimidante. En Casa de Oración te recibiremos como familia desde el primer momento.
    </p>
    <a href="#visitanos" class="bg-gold text-navy px-10 py-3.5 text-sm font-medium tracking-wide hover:bg-yellow-400 transition-colors duration-300 inline-block">Planifica tu primera visita</a>
  </div>
</section>

<div class="gold-divider"></div>

<!-- VISÍTANOS -->
<section id="visitanos" class="py-24 md:py-32 bg-cream">
  <div class="max-w-7xl mx-auto px-6 md:px-12">
    <div class="grid md:grid-cols-2 gap-16 items-start">

      <div class="fade-in">
        <p class="gold-line text-gold text-xs tracking-[.3em] uppercase font-sans">Encuéntranos</p>
        <h2 class="font-serif font-light text-4xl md:text-5xl text-navy leading-tight mb-10">
          Ven y <em class="italic text-gold">conócenos</em>
        </h2>

        <div class="space-y-8">
          <div class="flex gap-5">
            <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
              <p class="font-serif text-navy text-lg font-light mb-1">Dirección</p>
              <p class="text-navy/60 font-light text-sm leading-relaxed">{{ $churchAddress }}<br>{{ $churchCity }}</p>
            </div>
          </div>

          <div class="flex gap-5">
            <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
              <p class="font-serif text-navy text-lg font-light mb-2">Horarios de culto</p>
              <div class="space-y-1">
                @foreach(['Domingo — Culto principal'=>$scheduleSun,'Sábado — Oración'=>$scheduleSat,'Viernes — Jóvenes'=>$scheduleFri] as $dia=>$hora)
                <p class="text-navy/60 font-light text-sm flex justify-between max-w-xs"><span>{{ $dia }}</span><span class="text-navy font-normal">{{ $hora }}</span></p>
                @endforeach
              </div>
            </div>
          </div>

          <div class="flex gap-5">
            <div class="w-10 h-10 bg-navy rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.91a16 16 0 006.18 6.18l1.27-.83a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
            </div>
            <div>
              <p class="font-serif text-navy text-lg font-light mb-1">Contacto</p>
              <p class="text-navy/60 font-light text-sm">{{ $churchPhone }}</p>
              <p class="text-navy/60 font-light text-sm">{{ $churchEmail }}</p>
            </div>
          </div>
        </div>

        <div class="mt-12 border-t border-navy/10 pt-10">
          <h3 class="font-serif text-navy text-2xl font-light mb-6">Envíanos un mensaje</h3>
          <form class="space-y-4" onsubmit="submitForm(event)">
            <div class="grid grid-cols-2 gap-4">
              <input type="text" placeholder="Nombre" class="bg-white border border-navy/15 text-navy px-4 py-3 text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors w-full">
              <input type="email" placeholder="Email" class="bg-white border border-navy/15 text-navy px-4 py-3 text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors w-full">
            </div>
            <textarea rows="4" placeholder="¿En qué podemos ayudarte?" class="w-full bg-white border border-navy/15 text-navy px-4 py-3 text-sm font-light placeholder-navy/30 focus:outline-none focus:border-gold transition-colors resize-none"></textarea>
            <button type="submit" id="submit-btn" class="w-full bg-navy text-cream py-3 text-sm font-light tracking-wide hover:bg-gold hover:text-navy transition-all duration-300">Enviar mensaje</button>
          </form>
        </div>
      </div>

      <div class="space-y-6 fade-in">
        <div class="aspect-[4/3] relative overflow-hidden">
          @if($churchMapsEmbed)
            <iframe src="{{ $churchMapsEmbed }}" width="100%" height="100%" style="border:0;position:absolute;inset:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          @else
            <img src="https://picsum.photos/seed/mapa/800/600" alt="Ubicación" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-navy/20 flex items-center justify-center">
              <a href="{{ $churchMapsUrl ?: 'https://maps.google.com' }}" target="_blank" class="bg-white/90 backdrop-blur text-navy px-6 py-3 text-sm font-medium hover:bg-white transition-colors flex items-center gap-2">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Abrir en Google Maps
              </a>
            </div>
          @endif
        </div>

        <div class="bg-navy p-8">
          <h3 class="font-serif text-white text-xl font-light mb-5">¿Qué esperar en tu visita?</h3>
          <div class="space-y-4">
            @foreach([
              'Bienvenida cálida desde el estacionamiento hasta la entrada',
              'Adoración contemporánea en español con excelencia musical',
              'Mensaje bíblico práctico y relevante para tu vida',
              'Ministerio de niños seguro y dinámico (3 meses – 12 años)',
              'Café y convivencia después del culto dominical',
            ] as $item)
            <div class="flex gap-3 items-start">
              <span class="text-gold font-serif text-lg leading-none mt-0.5">—</span>
              <p class="text-white/60 font-light text-sm">{{ $item }}</p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="pt-16 pb-8" style="background:#111e30">
  <div class="max-w-7xl mx-auto px-6 md:px-12">
    <div class="grid md:grid-cols-4 gap-10 mb-12">
      <div class="md:col-span-2">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-8 rounded-full flex items-center justify-center" style="border:1px solid rgba(201,168,76,.5)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.5"><path d="M12 2L12 8M12 8C12 8 8 6 5 8C2 10 2 14 5 16C8 18 12 16 12 16M12 8C12 8 16 6 19 8C22 10 22 14 19 16C16 18 12 16 12 16M12 16L12 22"/></svg>
          </div>
          <span class="font-serif text-white text-lg tracking-wide">{{ $churchName }}</span>
        </div>
        <p class="font-light text-sm leading-relaxed max-w-xs mb-6" style="color:rgba(255,255,255,.4)">{{ $churchDesc }}</p>
      </div>

      <div>
        <p class="text-gold text-xs tracking-[.25em] uppercase font-sans mb-5">Navegación</p>
        <ul class="space-y-3">
          @foreach(['#acerca'=>'Acerca de nosotros','#eventos'=>'Eventos','#sermones'=>'Sermones','#visitanos'=>'Visítanos'] as $href=>$label)
          <li><a href="{{ $href }}" class="text-sm font-light transition-colors" style="color:rgba(255,255,255,.4)">{{ $label }}</a></li>
          @endforeach
        </ul>
      </div>

      <div>
        <p class="text-gold text-xs tracking-[.25em] uppercase font-sans mb-5">Contacto</p>
        <ul class="space-y-3">
          <li class="text-sm font-light" style="color:rgba(255,255,255,.4)">{{ $churchAddress }}, {{ $churchCity }}</li>
          <li class="text-sm font-light" style="color:rgba(255,255,255,.4)">{{ $churchPhone }}</li>
          <li class="text-sm font-light" style="color:rgba(255,255,255,.4)">{{ $churchEmail }}</li>
        </ul>
      </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4" style="border-top:1px solid rgba(255,255,255,.08);padding-top:2rem">
      <p class="text-xs font-light" style="color:rgba(255,255,255,.2)">© {{ date('Y') }} {{ $churchName }} · Todos los derechos reservados</p>
      <p class="text-xs font-light" style="color:rgba(255,255,255,.2)">Hecho con amor para la gloria de Dios</p>
    </div>
  </div>
</footer>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 60);
});

const observer = new IntersectionObserver((entries) => {
  entries.forEach((e, i) => {
    if (e.isIntersecting) {
      setTimeout(() => e.target.classList.add('visible'), i * 80);
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

function toggleMenu() {
  const m = document.getElementById('mobile-menu');
  const isOpen = m.style.display === 'flex';
  m.style.display = isOpen ? 'none' : 'flex';
  document.getElementById('icon-open').classList.toggle('hidden', !isOpen);
  document.getElementById('icon-close').classList.toggle('hidden', isOpen);
}
function closeMenu() {
  document.getElementById('mobile-menu').style.display = 'none';
  document.getElementById('icon-open').classList.remove('hidden');
  document.getElementById('icon-close').classList.add('hidden');
}

function submitForm(e) {
  e.preventDefault();
  const btn = document.getElementById('submit-btn');
  btn.textContent = '¡Mensaje enviado!';
  btn.style.background = '#c9a84c';
  btn.style.color = '#1a2e4a';
  e.target.reset();
  setTimeout(() => {
    btn.textContent = 'Enviar mensaje';
    btn.style.background = '';
    btn.style.color = '';
  }, 3000);
}
</script>
</body>
</html>
