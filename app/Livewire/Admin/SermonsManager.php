<?php

namespace App\Livewire\Admin;

use App\Models\Serie;
use App\Models\Sermon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class SermonsManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterSeries = '';
    public string $filterPublished = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int $sermon_id = null;
    public string $title = '';
    public string $speaker = '';
    public string $date = '';
    public ?int $series_id = null;
    public string $description = '';
    public string $bible_passage = '';
    public string $audio_url = '';
    public string $video_url = '';
    public string $duration_minutes = '';
    public bool $published = false;

    protected function rules(): array
    {
        return [
            'title'            => 'required|string|max:200',
            'speaker'          => 'required|string|max:100',
            'date'             => 'required|date',
            'series_id'        => 'nullable|exists:series,id',
            'description'      => 'nullable|string',
            'bible_passage'    => 'nullable|string|max:100',
            'audio_url'        => 'nullable|url|max:500',
            'video_url'        => 'nullable|url|max:500',
            'duration_minutes' => 'nullable|integer|min:1|max:300',
            'published'        => 'boolean',
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

        $sermon = Sermon::findOrFail($id);

        $this->sermon_id        = $sermon->id;
        $this->title            = $sermon->title;
        $this->speaker          = $sermon->speaker;
        $this->date             = $sermon->date->format('Y-m-d');
        $this->series_id        = $sermon->series_id;
        $this->description      = $sermon->description ?? '';
        $this->bible_passage    = $sermon->bible_passage ?? '';
        $this->audio_url        = $sermon->audio_url ?? '';
        $this->video_url        = $sermon->video_url ?? '';
        $this->duration_minutes = $sermon->duration_minutes ?? '';
        $this->published        = $sermon->published;

        $this->isEditing  = true;
        $this->showModal  = true;
    }

    public function save(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $data = $this->validate();
        $data['slug'] = Str::slug($data['title']);

        if ($this->isEditing) {
            Sermon::findOrFail($this->sermon_id)->update($data);
            session()->flash('success', 'Sermon updated successfully.');
        } else {
            Sermon::create($data);
            session()->flash('success', 'Sermon created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function togglePublished(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $sermon = Sermon::findOrFail($id);
        $sermon->update([
            'published'    => ! $sermon->published,
            'published_at' => ! $sermon->published ? now() : null,
        ]);
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        Sermon::findOrFail($id)->delete();
        session()->flash('success', 'Sermon deleted.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->sermon_id        = null;
        $this->title            = '';
        $this->speaker          = '';
        $this->date             = '';
        $this->series_id        = null;
        $this->description      = '';
        $this->bible_passage    = '';
        $this->audio_url        = '';
        $this->video_url        = '';
        $this->duration_minutes = '';
        $this->published        = false;
        $this->resetValidation();
    }

    public function render()
    {
        $sermons = Sermon::query()
            ->with('series')
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('speaker', 'like', "%{$this->search}%")
            )
            ->when($this->filterSeries, fn ($q) =>
                $q->where('series_id', $this->filterSeries)
            )
            ->when($this->filterPublished !== '', fn ($q) =>
                $q->where('published', (bool) $this->filterPublished)
            )
            ->orderByDesc('date')
            ->paginate(10);

        $allSeries = Serie::orderBy('title')->get();

        return view('livewire.admin.sermons-manager', [
            'sermons'   => $sermons,
            'allSeries' => $allSeries,
        ])->layout('layouts.admin', ['title' => 'Sermons']);
    }
}
