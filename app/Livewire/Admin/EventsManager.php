<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class EventsManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterPublished = '';

    protected $listeners = ['mediaSelected' => 'onMediaSelected'];

    public bool $showModal = false;
    public bool $isEditing = false;

    public bool $showRegistrations = false;
    public ?Event $registrationsEvent = null;
    public $registrations = [];

    public ?int $event_id = null;
    public string $title = '';
    public string $short_description = '';
    public string $description = '';
    public ?int $category_id = null;
    public string $start_date = '';
    public string $end_date = '';
    public bool $all_day = false;
    public string $location = '';
    public string $address = '';
    public string $maps_url = '';
    public string $image = '';
    public string $capacity = '';
    public bool $published = false;
    public bool $featured = false;
    public bool $requires_registration = false;

    protected function rules(): array
    {
        return [
            'title'                 => 'required|string|max:200',
            'short_description'     => 'nullable|string|max:300',
            'description'           => 'required|string',
            'category_id'           => 'nullable|exists:categories,id',
            'start_date'            => 'required|date',
            'end_date'              => 'nullable|date|after_or_equal:start_date',
            'all_day'               => 'boolean',
            'location'              => 'nullable|string|max:200',
            'address'               => 'nullable|string',
            'maps_url'              => 'nullable|url|max:500',
            'image'                 => 'nullable|url|max:255',
            'capacity'              => 'nullable|integer|min:1',
            'published'             => 'boolean',
            'featured'              => 'boolean',
            'requires_registration' => 'boolean',
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

        $event = Event::findOrFail($id);

        $this->event_id              = $event->id;
        $this->title                 = $event->title;
        $this->short_description     = $event->short_description ?? '';
        $this->description           = $event->description;
        $this->category_id           = $event->category_id;
        $this->start_date            = $event->start_date->format('Y-m-d\TH:i');
        $this->end_date              = $event->end_date?->format('Y-m-d\TH:i') ?? '';
        $this->all_day               = $event->all_day;
        $this->location              = $event->location ?? '';
        $this->address               = $event->address ?? '';
        $this->maps_url              = $event->maps_url ?? '';
        $this->image                 = $event->image ?? '';
        $this->capacity              = $event->capacity ?? '';
        $this->published             = $event->published;
        $this->featured              = $event->featured;
        $this->requires_registration = $event->requires_registration;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $data = $this->validate();

        $data['capacity']  = $data['capacity']  !== '' ? (int) $data['capacity']  : null;
        $data['end_date']  = $data['end_date']  !== '' ? $data['end_date']        : null;
        $data['maps_url']  = $data['maps_url']  !== '' ? $data['maps_url']        : null;
        $data['image']     = $data['image']     !== '' ? $data['image']           : null;
        $data['address']   = $data['address']   !== '' ? $data['address']         : null;
        $data['location']  = $data['location']  !== '' ? $data['location']        : null;
        $data['slug'] = Str::slug($data['title']);

        if ($this->isEditing) {
            Event::findOrFail($this->event_id)->update($data);
            session()->flash('success', 'Event updated successfully.');
        } else {
            Event::create($data);
            session()->flash('success', 'Event created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function togglePublished(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $event = Event::findOrFail($id);
        $event->update(['published' => ! $event->published]);
    }

    public function toggleFeatured(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $event = Event::findOrFail($id);
        $event->update(['featured' => ! $event->featured]);
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        Event::findOrFail($id)->delete();
        session()->flash('success', 'Event deleted.');
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

    public function openRegistrations(int $id): void
    {
        $this->registrationsEvent = Event::findOrFail($id);
        $this->registrations = EventRegistration::where('event_id', $id)
            ->orderByDesc('created_at')
            ->get();
        $this->showRegistrations = true;
    }

    public function closeRegistrations(): void
    {
        $this->showRegistrations = false;
        $this->registrationsEvent = null;
        $this->registrations = [];
    }

    private function resetForm(): void
    {
        $this->event_id              = null;
        $this->title                 = '';
        $this->short_description     = '';
        $this->description           = '';
        $this->category_id           = null;
        $this->start_date            = '';
        $this->end_date              = '';
        $this->all_day               = false;
        $this->location              = '';
        $this->address               = '';
        $this->maps_url              = '';
        $this->image                 = '';
        $this->capacity              = '';
        $this->published             = false;
        $this->featured              = false;
        $this->requires_registration = false;
        $this->resetValidation();
    }

    public function render()
    {
        $events = Event::query()
            ->with('category')
            ->withCount('registrations')
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('location', 'like', "%{$this->search}%")
            )
            ->when($this->filterCategory, fn ($q) =>
                $q->where('category_id', $this->filterCategory)
            )
            ->when($this->filterPublished !== '', fn ($q) =>
                $q->where('published', (bool) $this->filterPublished)
            )
            ->orderByDesc('start_date')
            ->paginate(10);

        $categories = Category::forEvents()->orderBy('name')->get();

        return view('livewire.admin.events-manager', [
            'events'     => $events,
            'categories' => $categories,
        ])->layout('layouts.admin', ['title' => 'Events']);
    }
}
