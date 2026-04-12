<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventRegistration;
use Livewire\Component;

class EventDetail extends Component
{
    public Event $event;

    public string $reg_name      = '';
    public string $reg_email     = '';
    public string $reg_phone     = '';
    public int    $reg_attendees = 1;
    public string $reg_notes     = '';

    public bool $submitted = false;
    public bool $alreadyRegistered = false;

    public function mount(string $slug): void
    {
        $this->event = Event::with('category')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
    }

    public function register(): void
    {
        $this->validate([
            'reg_name'      => 'required|string|max:100',
            'reg_email'     => 'required|email|max:150',
            'reg_phone'     => 'nullable|string|max:30',
            'reg_attendees' => 'required|integer|min:1|max:20',
            'reg_notes'     => 'nullable|string|max:500',
        ]);

        $exists = EventRegistration::where('event_id', $this->event->id)
            ->where('email', $this->reg_email)
            ->exists();

        if ($exists) {
            $this->alreadyRegistered = true;
            return;
        }

        EventRegistration::create([
            'event_id'  => $this->event->id,
            'name'      => $this->reg_name,
            'email'     => $this->reg_email,
            'phone'     => $this->reg_phone ?: null,
            'attendees' => $this->reg_attendees,
            'notes'     => $this->reg_notes ?: null,
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        $registrationsCount = EventRegistration::where('event_id', $this->event->id)->count();
        $attendeesCount     = (int) EventRegistration::where('event_id', $this->event->id)->sum('attendees');

        $seatsLeft = $this->event->capacity
            ? max(0, $this->event->capacity - $registrationsCount)
            : null;

        $related = Event::with('category')
            ->published()
            ->where('id', '!=', $this->event->id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        return view('livewire.event-detail', compact(
            'related',
            'registrationsCount',
            'attendeesCount',
            'seatsLeft',
        ))->layout('layouts.public');
    }
}
