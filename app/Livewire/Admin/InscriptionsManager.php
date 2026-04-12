<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Component;
use Livewire\WithPagination;

class InscriptionsManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterEvent = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int $registration_id = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public int $attendees = 1;
    public string $notes = '';
    public ?int $event_id = null;

    protected function rules(): array
    {
        return [
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|max:200',
            'phone'     => 'nullable|string|max:30',
            'attendees' => 'required|integer|min:1|max:100',
            'notes'     => 'nullable|string|max:1000',
            'event_id'  => 'required|exists:events,id',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterEvent(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $reg = EventRegistration::findOrFail($id);

        $this->registration_id = $reg->id;
        $this->name            = $reg->name;
        $this->email           = $reg->email;
        $this->phone           = $reg->phone ?? '';
        $this->attendees       = $reg->attendees;
        $this->notes           = $reg->notes ?? '';
        $this->event_id        = $reg->event_id;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $data = $this->validate();

        if ($this->isEditing) {
            EventRegistration::findOrFail($this->registration_id)->update($data);
            session()->flash('success', 'Inscripción actualizada.');
        } else {
            EventRegistration::create($data);
            session()->flash('success', 'Inscripción creada.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        EventRegistration::findOrFail($id)->delete();
        session()->flash('success', 'Inscripción eliminada.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function exportCsv()
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $query = EventRegistration::with('event')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
            )
            ->when($this->filterEvent, fn ($q) =>
                $q->where('event_id', $this->filterEvent)
            )
            ->orderByDesc('created_at')
            ->get();

        $filename = 'inscripciones_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // BOM para que Excel abra UTF-8 correctamente
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Evento', 'Nombre', 'Email', 'Teléfono', 'Asistentes', 'Notas', 'Fecha inscripción']);

            foreach ($query as $reg) {
                fputcsv($handle, [
                    $reg->event?->title ?? '—',
                    $reg->name,
                    $reg->email,
                    $reg->phone ?? '',
                    $reg->attendees,
                    $reg->notes ?? '',
                    $reg->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function resetForm(): void
    {
        $this->registration_id = null;
        $this->name            = '';
        $this->email           = '';
        $this->phone           = '';
        $this->attendees       = 1;
        $this->notes           = '';
        $this->event_id        = null;
        $this->resetValidation();
    }

    public function render()
    {
        $registrations = EventRegistration::with('event')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
            )
            ->when($this->filterEvent, fn ($q) =>
                $q->where('event_id', $this->filterEvent)
            )
            ->orderByDesc('created_at')
            ->paginate(15);

        $events = Event::orderBy('start_date', 'desc')->get(['id', 'title']);

        return view('livewire.admin.inscriptions-manager', [
            'registrations' => $registrations,
            'events'        => $events,
        ])->layout('layouts.admin', ['title' => 'Inscripciones']);
    }
}
