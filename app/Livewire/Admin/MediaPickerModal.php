<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;

class MediaPickerModal extends Component
{
    use WithPagination;

    public bool $show = false;
    public string $search = '';
    public string $targetField = '';

    protected $listeners = ['openMediaPicker' => 'open'];

    public function open(string $field): void
    {
        $this->targetField = $field;
        $this->search = '';
        $this->resetPage();
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        $this->search = '';
        $this->targetField = '';
    }

    public function selectImage(string $url): void
    {
        $this->dispatch('mediaSelected', field: $this->targetField, url: $url);
        $this->close();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $images = $this->show
            ? Media::where('mime_type', 'like', 'image/%')
                ->when($this->search, fn ($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                )
                ->latest()
                ->paginate(20)
            : collect();

        return view('livewire.admin.media-picker-modal', [
            'images' => $images,
        ]);
    }
}
