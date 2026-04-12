<?php

namespace App\Livewire;

use App\Models\Sermon;
use Livewire\Component;

class SermonDetail extends Component
{
    public Sermon $sermon;

    public function mount(string $slug): void
    {
        $this->sermon = Sermon::with('series')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
    }

    public function render()
    {
        $related = Sermon::with('series')
            ->published()
            ->where('id', '!=', $this->sermon->id)
            ->when($this->sermon->series_id, fn ($q) =>
                $q->where('series_id', $this->sermon->series_id)
            )
            ->orderByDesc('date')
            ->limit(3)
            ->get();

        return view('livewire.sermon-detail', compact('related'))
            ->layout('layouts.public');
    }
}
