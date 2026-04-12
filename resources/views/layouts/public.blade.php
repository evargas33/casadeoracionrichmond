@php
use App\Models\Setting;
$churchName      = Setting::get('church_name',    'Casa de Oración');
$churchTagline   = Setting::get('church_tagline', 'Iglesia Evangélica · Bay Area');
$socialFb        = Setting::get('social_facebook',  '');
$socialIg        = Setting::get('social_instagram',  '');
$socialYt        = Setting::get('social_youtube',    '');
$metaTitle       = $metaTitle  ?? ($churchName . ' — ' . $churchTagline);
$metaDesc        = $metaDesc   ?? Setting::get('meta_description', '');
$isLive          = (bool) Setting::get('live_stream_active', false)
                   && Setting::get('live_stream_video_id', '') !== '';
$menuPages       = \App\Models\Page::inMenu()->get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $metaDesc }}">
<title>{{ $pageTitle ?? 'Sermones' }} — {{ $churchName }}</title>
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
  #pub-nav { transition: background .4s, box-shadow .4s; }
  #pub-nav.scrolled { background: rgba(26,46,74,0.97); box-shadow: 0 2px 24px rgba(0,0,0,.25); }
  .nav-link { position:relative; }
  .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:1px; background:#c9a84c; transition:width .3s; }
  .nav-link:hover::after { width:100%; }
  .gold-line::before { content:''; display:block; width:56px; height:2px; background:#c9a84c; margin-bottom:1rem; }
  .fade-in { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
  .fade-in.visible { opacity: 1; transform: translateY(0); }
  .card-hover { transition: transform .3s ease, box-shadow .3s ease; }
  .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(26,46,74,.14); }
</style>
@stack('styles')
</head>
<body>

{{-- ── NAV ── --}}
<nav id="pub-nav" class="fixed top-0 w-full z-50 py-4 px-6 md:px-12 bg-navy/95">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <a href="{{ url('/') }}" class="flex items-center gap-3">
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
      <a href="{{ url('/') }}#acerca"    class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Acerca</a>
      <a href="{{ route('events.index') }}" class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Eventos</a>
      <a href="{{ route('sermons.index') }}" class="nav-link text-white text-sm font-light tracking-wide transition-colors">Sermones</a>
      <a href="{{ url('/') }}#visitanos" class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors">Visítanos</a>
      @foreach($menuPages as $menuPage)
        <a href="{{ route('page.show', $menuPage->slug) }}"
           class="nav-link text-white/80 hover:text-white text-sm font-light tracking-wide transition-colors
                  {{ request()->routeIs('page.show') && request()->route('slug') === $menuPage->slug ? 'text-white' : '' }}">
          {{ $menuPage->title }}
        </a>
      @endforeach
      @auth
        <a href="{{ route('admin.dashboard') }}" class="border border-gold text-gold px-5 py-1.5 text-sm font-light tracking-wide hover:bg-gold hover:text-navy transition-all duration-300">Panel</a>
      @else
        <a href="{{ route('login') }}" class="border border-gold text-gold px-5 py-1.5 text-sm font-light tracking-wide hover:bg-gold hover:text-navy transition-all duration-300">Iniciar sesión</a>
      @endauth
    </div>

    <button id="pub-menu-btn" class="md:hidden text-white" onclick="togglePubMenu()">
      <svg id="pub-icon-open"  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="7" x2="21" y2="7"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="17" x2="21" y2="17"/></svg>
      <svg id="pub-icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none"><line x1="4" y1="4" x2="20" y2="20"/><line x1="20" y1="4" x2="4" y2="20"/></svg>
    </button>
  </div>

  <div id="pub-mobile-menu" style="display:none" class="mt-4 pb-4 border-t border-white/10 flex-col gap-4 pt-4">
    @if($isLive)
      <a href="{{ route('live') }}" class="flex items-center gap-1.5 text-red-400 text-sm tracking-wide font-semibold">
        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
        En vivo
      </a>
    @endif
    <a href="{{ url('/') }}#acerca"    class="text-white/80 text-sm tracking-wide block">Acerca</a>
    <a href="{{ url('/') }}#eventos"   class="text-white/80 text-sm tracking-wide block">Eventos</a>
    <a href="{{ route('sermons.index') }}" class="text-white text-sm tracking-wide block">Sermones</a>
    <a href="{{ url('/') }}#visitanos" class="text-white/80 text-sm tracking-wide block">Visítanos</a>
    @foreach($menuPages as $menuPage)
      <a href="{{ route('page.show', $menuPage->slug) }}" class="text-white/80 text-sm tracking-wide block">
        {{ $menuPage->title }}
      </a>
    @endforeach
    @auth
      <a href="{{ route('admin.dashboard') }}" class="text-gold text-sm tracking-wide block">Panel de administración</a>
    @else
      <a href="{{ route('login') }}" class="text-gold text-sm tracking-wide block">Iniciar sesión</a>
    @endauth
  </div>
</nav>

{{-- ── CONTENT ── --}}
{{ $slot }}

{{-- ── FOOTER ── --}}
<footer class="pt-12 pb-8" style="background:#111e30">
  <div class="max-w-7xl mx-auto px-6 md:px-12">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4" style="border-top:1px solid rgba(255,255,255,.08);padding-top:2rem">
      <div class="flex items-center gap-3">
        <div class="w-7 h-7 rounded-full flex items-center justify-center" style="border:1px solid rgba(201,168,76,.4)">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.5"><path d="M12 2L12 8M12 8C12 8 8 6 5 8C2 10 2 14 5 16C8 18 12 16 12 16M12 8C12 8 16 6 19 8C22 10 22 14 19 16C16 18 12 16 12 16M12 16L12 22"/></svg>
        </div>
        <p class="text-xs font-light" style="color:rgba(255,255,255,.3)">© {{ date('Y') }} {{ $churchName }} · Todos los derechos reservados</p>
      </div>
      <div class="flex items-center gap-5">
        @if($socialFb)
          <a href="{{ $socialFb }}" target="_blank" class="text-white/30 hover:text-gold transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
          </a>
        @endif
        @if($socialIg)
          <a href="{{ $socialIg }}" target="_blank" class="text-white/30 hover:text-gold transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
          </a>
        @endif
        @if($socialYt)
          <a href="{{ $socialYt }}" target="_blank" class="text-white/30 hover:text-gold transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#111e30"/></svg>
          </a>
        @endif
      </div>
    </div>
  </div>
</footer>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('pub-nav').classList.toggle('scrolled', window.scrollY > 60);
});
const obs = new IntersectionObserver((entries) => {
  entries.forEach((e, i) => {
    if (e.isIntersecting) { setTimeout(() => e.target.classList.add('visible'), i * 60); obs.unobserve(e.target); }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach(el => obs.observe(el));

function togglePubMenu() {
  const m = document.getElementById('pub-mobile-menu');
  const open = m.style.display === 'flex';
  m.style.display = open ? 'none' : 'flex';
  document.getElementById('pub-icon-open').style.display  = open ? 'block' : 'none';
  document.getElementById('pub-icon-close').style.display = open ? 'none'  : 'block';
}
</script>
@stack('scripts')
@livewireScripts
</body>
</html>
