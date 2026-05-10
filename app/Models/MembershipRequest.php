<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipRequest extends Model
{
    protected $fillable = [
        'full_name',
        'address',
        'city',
        'zip_code',
        'birth_date',
        'phone',
        'email',
        'marital_status',
        'spouse_name',
        'has_children',
        'children_names',
        'received_jesus',
        'baptized_water',
        'baptism_church',
        'has_served_ministry',
        'wants_serve_ministry',
        'emergency_contact_name',
        'emergency_contact_phone',
        'commitment_accepted',
        'signature',
        'submission_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'submission_date' => 'date',
        'approved_at' => 'datetime',
        'has_children' => 'boolean',
        'received_jesus' => 'boolean',
        'baptized_water' => 'boolean',
        'has_served_ministry' => 'boolean',
        'wants_serve_ministry' => 'boolean',
        'commitment_accepted' => 'boolean',
    ];

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
