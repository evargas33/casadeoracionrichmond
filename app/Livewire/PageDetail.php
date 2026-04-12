<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\Setting;
use Livewire\Component;

class PageDetail extends Component
{
    public Page $page;

    public function mount(string $slug): void
    {
        $this->page = Page::where('slug', $slug)
            ->published()
            ->firstOrFail();
    }

    public function render()
    {
        $settings = [
            'church_name'    => Setting::get('church_name', 'Casa de Oración'),
            'church_address' => Setting::get('church_address', ''),
            'church_city'    => Setting::get('church_city', ''),
            'church_phone'   => Setting::get('church_phone', ''),
            'church_email'   => Setting::get('church_email', ''),
            'church_maps_url'   => Setting::get('church_maps_url', ''),
            'church_maps_embed' => Setting::get('church_maps_embed', ''),
            'schedule_sunday'   => Setting::get('schedule_sunday', ''),
            'schedule_saturday' => Setting::get('schedule_saturday', ''),
            'schedule_friday'   => Setting::get('schedule_friday', ''),
            'social_facebook'   => Setting::get('social_facebook', ''),
            'social_instagram'  => Setting::get('social_instagram', ''),
            'social_youtube'    => Setting::get('social_youtube', ''),
        ];

        return view('livewire.page-detail', compact('settings'))
            ->layout('layouts.public', [
                'pageTitle' => $this->page->title,
                'metaTitle' => $this->page->meta_title ?: $this->page->title,
                'metaDesc'  => $this->page->meta_description ?: '',
            ]);
    }
}
