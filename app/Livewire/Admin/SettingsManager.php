<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class SettingsManager extends Component
{
    // Identidad
    public string $church_name = '';
    public string $church_tagline = '';
    public string $church_description = '';
    public string $church_founded = '';
    public string $pastor_name = '';
    public string $pastor_title = '';

    // Contacto
    public string $church_address = '';
    public string $church_city = '';
    public string $church_phone = '';
    public string $church_email = '';
    public string $church_maps_url = '';
    public string $church_maps_embed = '';

    // Horarios
    public string $schedule_sunday = '';
    public string $schedule_saturday = '';
    public string $schedule_friday = '';

    // Redes sociales
    public string $social_facebook = '';
    public string $social_instagram = '';
    public string $social_youtube = '';

    // Transmisión en vivo
    public bool   $live_stream_active   = false;
    public string $live_stream_video_id = '';

    // SEO
    public string $meta_title = '';
    public string $meta_description = '';

    public function mount(): void
    {
        $keys = [
            'church_name', 'church_tagline', 'church_description', 'church_founded',
            'pastor_name', 'pastor_title',
            'church_address', 'church_city', 'church_phone', 'church_email', 'church_maps_url', 'church_maps_embed',
            'schedule_sunday', 'schedule_saturday', 'schedule_friday',
            'social_facebook', 'social_instagram', 'social_youtube',
            'live_stream_video_id',
            'meta_title', 'meta_description',
        ];

        foreach ($keys as $key) {
            $this->$key = Setting::get($key, '');
        }

        $this->live_stream_active = (bool) Setting::get('live_stream_active', false);
    }

    public function save(): void
    {
        $this->validate([
            'church_name'        => 'required|string|max:255',
            'church_tagline'     => 'nullable|string|max:255',
            'church_description' => 'nullable|string|max:1000',
            'church_founded'     => 'nullable|string|max:10',
            'pastor_name'        => 'nullable|string|max:255',
            'pastor_title'       => 'nullable|string|max:255',
            'church_address'     => 'nullable|string|max:255',
            'church_city'        => 'nullable|string|max:255',
            'church_phone'       => 'nullable|string|max:50',
            'church_email'       => 'nullable|string|email|max:255',
            'church_maps_url'    => 'nullable|string|max:500',
            'church_maps_embed'  => 'nullable|string|max:2000',
            'schedule_sunday'    => 'nullable|string|max:50',
            'schedule_saturday' => 'nullable|string|max:50',
            'schedule_friday'    => 'nullable|string|max:50',
            'social_facebook'    => 'nullable|string|max:500',
            'social_instagram'   => 'nullable|string|max:500',
            'social_youtube'       => 'nullable|string|max:500',
            'live_stream_active'   => 'boolean',
            'live_stream_video_id' => 'nullable|string|max:50',
            'meta_title'           => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
        ]);

        Setting::setMany([
            'church_name'        => $this->church_name,
            'church_tagline'     => $this->church_tagline,
            'church_description' => $this->church_description,
            'church_founded'     => $this->church_founded,
            'pastor_name'        => $this->pastor_name,
            'pastor_title'       => $this->pastor_title,
            'church_address'     => $this->church_address,
            'church_city'        => $this->church_city,
            'church_phone'       => $this->church_phone,
            'church_email'       => $this->church_email,
            'church_maps_url'    => $this->church_maps_url,
            'church_maps_embed'  => $this->church_maps_embed,
            'schedule_sunday'    => $this->schedule_sunday,
            'schedule_saturday' => $this->schedule_saturday,
            'schedule_friday'    => $this->schedule_friday,
            'social_facebook'    => $this->social_facebook,
            'social_instagram'   => $this->social_instagram,
            'social_youtube'       => $this->social_youtube,
            'live_stream_active'   => $this->live_stream_active ? '1' : '0',
            'live_stream_video_id' => $this->live_stream_video_id,
            'meta_title'           => $this->meta_title,
            'meta_description'   => $this->meta_description,
        ]);

        session()->flash('saved', true);
    }

    public function render()
    {
        return view('livewire.admin.settings-manager')
            ->layout('layouts.admin', ['title' => 'Settings']);
    }
}
