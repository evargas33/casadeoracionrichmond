<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Sermon;
use App\Models\Setting;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // ── Stat cards ──────────────────────────────────────────
        $stats = [
            'sermons_published'    => Sermon::where('published', true)->count(),
            'events_upcoming'      => Event::published()->where('start_date', '>=', now())->count(),
            'registrations_total'  => EventRegistration::count(),
            'registrations_month'  => EventRegistration::whereBetween('created_at', [
                now()->startOfMonth(), now()->endOfMonth(),
            ])->count(),
            'attendees_total'      => (int) EventRegistration::sum('attendees'),
        ];

        // ── Inscripciones por evento (solo eventos con al menos 1) ──
        $eventRegistrations = Event::withCount('registrations')
            ->withSum('registrations', 'attendees')
            ->whereHas('registrations')
            ->orderByDesc('registrations_count')
            ->limit(8)
            ->get();

        // ── Últimas inscripciones ────────────────────────────────
        $recentRegistrations = EventRegistration::with('event')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // ── Próximos eventos ─────────────────────────────────────
        $upcomingEvents = Event::published()
            ->with('category')
            ->withCount('registrations')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // ── Sermones recientes ───────────────────────────────────
        $recentSermons = Sermon::where('published', true)
            ->latest('date')
            ->limit(5)
            ->get();

        // ── Estado EN VIVO ───────────────────────────────────────
        $isLive    = (bool) Setting::get('live_stream_active', false)
                     && Setting::get('live_stream_video_id', '') !== '';
        $videoId   = Setting::get('live_stream_video_id', '');

        return view('livewire.admin.dashboard', compact(
            'stats',
            'eventRegistrations',
            'recentRegistrations',
            'upcomingEvents',
            'recentSermons',
            'isLive',
            'videoId',
        ))->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
