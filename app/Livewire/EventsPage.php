<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventsPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $tab = 'upcoming'; // upcoming | past

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterCategory(): void { $this->resetPage(); }
    public function updatingTab(): void { $this->resetPage(); }

    public function render()
    {
        $events = Event::with('category')
            ->withCount('registrations')
            ->published()
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('location', 'like', "%{$this->search}%")
            )
            ->when($this->filterCategory, fn ($q) =>
                $q->where('category_id', $this->filterCategory)
            )
            ->when($this->tab === 'upcoming',
                fn ($q) => $q->where('start_date', '>=', now())->orderBy('start_date'),
                fn ($q) => $q->where('start_date', '<', now())->orderByDesc('start_date')
            )
            ->paginate(9);

        $categories = Category::forEvents()->orderBy('name')->get();

        return view('livewire.events-page', compact('events', 'categories'))
            ->layout('layouts.public');
    }
}
