<?php

namespace App\Livewire;

use App\Mail\MembershipRequestConfirmation;
use App\Models\MembershipRequest;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class MembershipPage extends Component
{
    public string $full_name = '';
    public string $address = '';
    public string $city = '';
    public string $zip_code = '';
    public string $birth_date = '';
    public string $phone = '';
    public string $email = '';
    public string $marital_status = '';
    public string $spouse_name = '';
    public bool $has_children = false;
    public string $children_names = '';
    public bool $received_jesus = false;
    public bool $baptized_water = false;
    public string $baptism_church = '';
    public bool $has_served_ministry = false;
    public bool $wants_serve_ministry = false;
    public string $emergency_contact_name = '';
    public string $emergency_contact_phone = '';
    public bool $commitment_accepted = false;
    public string $signature = '';
    public string $submission_date = '';
    public bool $submitted = false;

    public function mount(): void
    {
        $this->submission_date = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'full_name' => 'required|string|max:200',
            'address' => 'required|string|max:300',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'birth_date' => 'required|date|before:today',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:200|unique:membership_requests,email',
            'marital_status' => 'required|in:casado,soltero',
            'spouse_name' => 'nullable|string|max:150',
            'has_children' => 'nullable|boolean',
            'children_names' => 'nullable|string|max:1000',
            'received_jesus' => 'required|boolean',
            'baptized_water' => 'required|boolean',
            'baptism_church' => 'nullable|string|max:200',
            'has_served_ministry' => 'nullable|boolean',
            'wants_serve_ministry' => 'nullable|boolean',
            'emergency_contact_name' => 'required|string|max:200',
            'emergency_contact_phone' => 'required|string|max:30',
            'commitment_accepted' => 'required|boolean',
            'signature' => 'required|string|max:200',
            'submission_date' => 'nullable|date',
        ];
    }

    public function submit(): void
    {
        $data = $this->validate();

        // Siempre usar la fecha del servidor, no la del cliente
        $data['submission_date'] = now()->format('Y-m-d');

        $membership = MembershipRequest::create($data);
        Mail::to($membership->email)->send(new MembershipRequestConfirmation($membership));

        $this->submitted = true;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.membership-page')
            ->layout('layouts.public', ['pageTitle' => 'Solicitud de Membresía']);
    }
}
