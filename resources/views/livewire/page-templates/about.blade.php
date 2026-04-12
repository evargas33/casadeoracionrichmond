{{-- Template: about — Acerca de nosotros --}}

{{-- Hero --}}
<section class="relative pt-36 pb-20 overflow-hidden" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 60%, #243d61 100%);">
  <div class="max-w-4xl mx-auto px-6 md:px-12 text-center relative z-10">
    <p class="text-gold/80 text-xs tracking-[.35em] uppercase font-sans mb-4">{{ $settings['church_name'] }}</p>
    <h1 class="font-serif font-light text-5xl md:text-6xl text-white leading-tight mb-5">{{ $page->title }}</h1>
    @if($page->meta_description)
      <p class="text-white/60 font-light text-base max-w-xl mx-auto">{{ $page->meta_description }}</p>
    @endif
  </div>
</section>

{{-- Imagen destacada --}}
@if($page->og_image)
<section class="bg-cream">
  <div class="max-w-5xl mx-auto px-6 md:px-12 -mt-10">
    <div class="aspect-[21/9] w-full overflow-hidden shadow-2xl">
      <img src="{{ $page->og_image }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
    </div>
  </div>
</section>
@endif

{{-- Contenido --}}
<section class="py-16 bg-cream">
  <div class="max-w-3xl mx-auto px-6 md:px-12">
    <div class="prose prose-lg max-w-none font-light leading-relaxed text-navy/80 page-content">
      {!! $page->content !!}
    </div>
  </div>
</section>
