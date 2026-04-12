<?php

namespace App\Livewire\Admin;

use App\Models\Serie;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class SeriesManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterActive = '';

    protected $listeners = ['mediaSelected' => 'onMediaSelected'];

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int $serie_id = null;
    public string $title = '';
    public string $description = '';
    public string $image = '';
    public bool $active = true;
    public int $order = 0;

    protected function rules(): array
    {
        return [
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'image'       => 'nullable|url|max:255',
            'active'      => 'boolean',
            'order'       => 'integer|min:0',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $serie = Serie::findOrFail($id);

        $this->serie_id    = $serie->id;
        $this->title       = $serie->title;
        $this->description = $serie->description ?? '';
        $this->image       = $serie->image ?? '';
        $this->active      = $serie->active;
        $this->order       = $serie->order;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $data = $this->validate();
        $data['slug'] = Str::slug($data['title']);

        if ($this->isEditing) {
            Serie::findOrFail($this->serie_id)->update($data);
            session()->flash('success', 'Series updated successfully.');
        } else {
            Serie::create($data);
            session()->flash('success', 'Series created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $serie = Serie::findOrFail($id);
        $serie->update(['active' => ! $serie->active]);
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        Serie::findOrFail($id)->delete();
        session()->flash('success', 'Series deleted.');
    }

    public function onMediaSelected(string $field, string $url): void
    {
        if (property_exists($this, $field)) {
            $this->$field = $url;
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->serie_id    = null;
        $this->title       = '';
        $this->description = '';
        $this->image       = '';
        $this->active      = true;
        $this->order       = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $series = Serie::query()
            ->withCount('sermons')
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
            )
            ->when($this->filterActive !== '', fn ($q) =>
                $q->where('active', (bool) $this->filterActive)
            )
            ->orderBy('order')
            ->orderBy('title')
            ->paginate(10);

        return view('livewire.admin.series-manager', [
            'series' => $series,
        ])->layout('layouts.admin', ['title' => 'Series' ]);
    }
}
