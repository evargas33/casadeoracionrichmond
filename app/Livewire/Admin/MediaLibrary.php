<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;

class MediaLibrary extends Component
{
    use WithPagination;

    public string $filterType = '';
    public string $search     = '';

    public function updatingSearch(): void   { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }

    public function render()
    {
        $mediaItems = Media::query()
            ->with('uploader')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
            )
            ->when($this->filterType === 'image', fn ($q) =>
                $q->where('mime_type', 'like', 'image/%')
            )
            ->when($this->filterType === 'audio', fn ($q) =>
                $q->where('mime_type', 'like', 'audio/%')
            )
            ->when($this->filterType === 'video', fn ($q) =>
                $q->where('mime_type', 'like', 'video/%')
            )
            ->latest()
            ->paginate(24);

        return view('livewire.admin.media-library', compact('mediaItems'))
            ->layout('layouts.admin', ['title' => 'Media Library']);
    }
}
