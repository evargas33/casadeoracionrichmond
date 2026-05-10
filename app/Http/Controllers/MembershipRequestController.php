<?php

namespace App\Http\Controllers;

use App\Mail\MembershipRequestConfirmation;
use App\Models\MembershipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MembershipRequestController extends Controller
{
    public function create()
    {
        return view('membership.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:200',
            'address' => 'required|string|max:300',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:200',
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
            'submission_date' => 'required|date',
        ]);

        $membership = MembershipRequest::create($validated);

        Mail::to($membership->email)->send(new MembershipRequestConfirmation($membership));

        return redirect()->route('membership.create')->with('success', 'Solicitud de membresía enviada exitosamente. Recibirás un correo de confirmación.');
    }
}
