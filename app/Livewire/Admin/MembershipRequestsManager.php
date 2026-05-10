<?php

namespace App\Livewire\Admin;

use App\Mail\MembershipApprovalNotification;
use App\Models\MembershipRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class MembershipRequestsManager extends Component
{
    use WithPagination;

    #[\Livewire\Attributes\Validate('nullable|string|max:100')]
    public string $search = '';
    public string $filterStatus = '';

    public bool $showDetailModal = false;
    public bool $showRejectModal = false;

    public ?int $request_id = null;
    public string $rejection_reason = '';
    public ?MembershipRequest $selectedRequest = null;

    protected function rules(): array
    {
        return [
            'rejection_reason' => 'required|string|max:1000',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function viewDetail(int $id): void
    {
        $this->selectedRequest = MembershipRequest::findOrFail($id);
        $this->request_id = $id;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedRequest = null;
        $this->request_id = null;
    }

    public function openRejectModal(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $this->request_id = $id;
        $this->rejection_reason = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->request_id = null;
        $this->rejection_reason = '';
        $this->resetValidation();
    }

    public function approve(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $membership = MembershipRequest::findOrFail($id);
        abort_if($membership->status !== 'pending', 403);

        // Generar token de reset de contraseña
        $resetToken = Str::random(64);

        // Verificar si el usuario ya existe
        $user = User::where('email', $membership->email)->first();

        if (! $user) {
            // Crear usuario si no existe (sin contraseña)
            $user = User::create([
                'name' => $membership->full_name,
                'email' => $membership->email,
                'password' => Hash::make(Str::random(32)), // Password aleatoria sin usar
                'is_active' => true,
            ]);

            // Asignar rol de member
            $memberRole = Role::where('name', 'member')->firstOrFail();
            $user->roles()->attach($memberRole);

            // Crear token de reset de contraseña
            \DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => Hash::make($resetToken),
                'created_at' => now(),
            ]);

            // Enviar email con link de reset (NO con contraseña)
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new MembershipApprovalNotification($user, $resetToken));
        }

        // Actualizar solicitud
        $membership->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'user_id' => $user->id,
        ]);

        session()->flash('success', 'Solicitud aprobada.');
    }

    public function reject(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        // Validar que existe una solicitud válida
        $membership = MembershipRequest::where('id', $this->request_id)
            ->where('status', 'pending')
            ->firstOrFail();

        $data = $this->validate();

        $membership->update([
            'status' => 'rejected',
            'rejection_reason' => $data['rejection_reason'],
        ]);

        session()->flash('success', 'Solicitud rechazada.');
        $this->closeRejectModal();
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin']), 403);

        MembershipRequest::findOrFail($id)->delete();
        session()->flash('success', 'Solicitud eliminada.');
    }

    private function escapeCsvFormula(string $value): string
    {
        // Prevenir CSV injection (CWE-1236)
        if (preg_match('/^[=+@\-\t\r]/', $value)) {
            return "'" . $value;
        }
        return $value;
    }

    public function exportCsv()
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $query = MembershipRequest::query()
            ->when($this->search, fn ($q) =>
                $q->where('full_name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus, fn ($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->orderByDesc('created_at')
            ->get();

        $filename = 'solicitudes_membresia_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // BOM para que Excel abra UTF-8 correctamente
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'Nombre',
                'Email',
                'Teléfono',
                'Estado Civil',
                'Ciudad',
                'Estado',
                'Fecha Solicitud',
                'Aprobado Por',
                'Fecha Aprobación',
            ]);

            foreach ($query as $req) {
                fputcsv($handle, [
                    $this->escapeCsvFormula($req->full_name),
                    $this->escapeCsvFormula($req->email),
                    $this->escapeCsvFormula($req->phone),
                    $this->escapeCsvFormula($req->marital_status),
                    $this->escapeCsvFormula($req->city),
                    $req->status,
                    $req->submission_date->format('d/m/Y'),
                    $this->escapeCsvFormula($req->approvedBy?->name ?? ''),
                    $req->approved_at?->format('d/m/Y H:i') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        $requests = MembershipRequest::query()
            ->when($this->search, fn ($q) =>
                $q->where('full_name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus, fn ($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.membership-requests-manager', [
            'requests' => $requests,
        ])->layout('layouts.admin', ['title' => 'Solicitudes de Membresía']);
    }
}
