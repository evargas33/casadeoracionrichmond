<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UsersManager extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $filterRole  = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int  $user_id  = null;
    public string $name    = '';
    public string $email   = '';
    public string $password = '';
    public bool  $is_active = true;
    public array $selectedRoles = [];

    public function updatingSearch(): void { $this->resetPage(); }

    protected function rules(): array
    {
        $uniqueEmail = $this->isEditing
            ? 'required|email|unique:users,email,' . $this->user_id
            : 'required|email|unique:users,email';

        return [
            'name'          => 'required|string|max:255',
            'email'         => $uniqueEmail,
            'password'      => $this->isEditing ? 'nullable|min:8' : 'required|min:8',
            'is_active'     => 'boolean',
            'selectedRoles' => 'array',
            'selectedRoles.*' => 'exists:roles,id',
        ];
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

        $user = User::with('roles')->findOrFail($id);

        $this->user_id       = $user->id;
        $this->name          = $user->name;
        $this->email         = $user->email;
        $this->password      = '';
        $this->is_active     = $user->is_active ?? true;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $data = $this->validate();

        if ($this->isEditing) {
            $user = User::findOrFail($this->user_id);
            $user->update([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'is_active' => $data['is_active'],
            ]);
            if (! empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }
            $user->roles()->sync($data['selectedRoles']);
            session()->flash('success', 'Usuario actualizado.');
        } else {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'is_active' => $data['is_active'],
            ]);
            $user->roles()->sync($data['selectedRoles']);
            session()->flash('success', 'Usuario creado.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);
        abort_if($id === auth()->id(), 403); // No desactivarse a sí mismo

        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin']), 403);
        abort_if($id === auth()->id(), 403); // No borrarse a sí mismo

        User::findOrFail($id)->delete();
        session()->flash('success', 'Usuario eliminado.');
    }

    private function resetForm(): void
    {
        $this->user_id       = null;
        $this->name          = '';
        $this->email         = '';
        $this->password      = '';
        $this->is_active     = true;
        $this->selectedRoles = [];
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->when($this->filterRole, fn ($q) =>
                $q->whereHas('roles', fn ($r) => $r->where('id', $this->filterRole))
            )
            ->latest()
            ->paginate(15);

        return view('livewire.admin.users-manager', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Usuarios']);
    }
}
