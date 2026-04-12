<?php

namespace App\Livewire;

use App\Models\Serie;
use App\Models\Sermon;
use Livewire\Component;
use Livewire\WithPagination;

class SermonsPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterSeries = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterSeries(): void { $this->resetPage(); }

    public function render()
    {
        $sermons = Sermon::with('series')
            ->published()
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('speaker', 'like', "%{$this->search}%")
                  ->orWhere('bible_passage', 'like', "%{$this->search}%")
            )
            ->when($this->filterSeries, fn ($q) =>
                $q->where('series_id', $this->filterSeries)
            )
            ->orderByDesc('date')
            ->paginate(12);

        $allSeries = Serie::orderBy('title')->get();

        return view('livewire.sermons-page', compact('sermons', 'allSeries'))
            ->layout('layouts.public');
    }
}
