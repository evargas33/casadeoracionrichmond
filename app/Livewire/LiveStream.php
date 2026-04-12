<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Models\Sermon;
use Livewire\Component;

class LiveStream extends Component
{
    public function render()
    {
        $isLive   = (bool) Setting::get('live_stream_active', false);
        $videoId  = Setting::get('live_stream_video_id', '');
        $sermons  = Sermon::with('series')->published()->orderByDesc('date')->limit(4)->get();

        return view('livewire.live-stream', compact('isLive', 'videoId', 'sermons'))
            ->layout('layouts.public', ['pageTitle' => 'En Vivo']);
    }
}
