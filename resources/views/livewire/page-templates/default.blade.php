{{-- Template: default — Página de contenido genérica --}}

{{-- Hero --}}
<section class="pt-32 pb-14" style="background: linear-gradient(135deg, #111e30 0%, #1a2e4a 100%);">
  <div class="max-w-3xl mx-auto px-6 md:px-12 text-center">
    @if($page->category)
      <p class="text-gold/80 text-xs tracking-[.35em] uppercase font-sans mb-4">{{ $page->category->name }}</p>
    @endif
    <h1 class="font-serif font-light text-4xl md:text-5xl text-white leading-tight">{{ $page->title }}</h1>
  </div>
</section>

{{-- Contenido --}}
<section class="py-16 bg-cream">
  <div class="max-w-3xl mx-auto px-6 md:px-12">

    @if($page->og_image)
      <div class="aspect-[16/9] w-full overflow-hidden shadow-xl mb-12">
        <img src="{{ $page->og_image }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
      </div>
    @endif

    <div class="prose prose-lg max-w-none font-light leading-relaxed text-navy/80 page-content">
      {!! $page->content !!}
    </div>

  </div>
</section>
