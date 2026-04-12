<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class ProfileManager extends Component
{
    protected $listeners = ['mediaSelected' => 'onMediaSelected'];

    // Info
    public string $name   = '';
    public string $email  = '';
    public string $avatar = '';

    // Contraseña
    public string $current_password  = '';
    public string $new_password      = '';
    public string $confirm_password  = '';

    public bool $savedInfo     = false;
    public bool $savedPassword = false;
    public ?string $passwordError = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->name   = $user->name;
        $this->email  = $user->email;
        $this->avatar = $user->avatar ?? '';
    }

    public function onMediaSelected(string $field, string $url): void
    {
        if ($field === 'avatar') {
            $this->avatar = $url;
        }
    }

    public function saveInfo(): void
    {
        $user = auth()->user();

        $this->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name'   => $this->name,
            'email'  => $this->email,
            'avatar' => $this->avatar ?: null,
        ]);

        $this->savedInfo = true;
        $this->savedPassword = false;
    }

    public function savePassword(): void
    {
        $this->savedInfo     = false;
        $this->passwordError = null;

        $this->validate([
            'current_password' => 'required',
            'new_password'     => ['required', Password::min(8)],
            'confirm_password' => 'required|same:new_password',
        ], [
            'confirm_password.same' => 'La confirmación no coincide con la nueva contraseña.',
        ]);

        if (! Hash::check($this->current_password, auth()->user()->password)) {
            $this->passwordError = 'La contraseña actual es incorrecta.';
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password     = '';
        $this->confirm_password = '';
        $this->savedPassword    = true;
    }

    public function render()
    {
        return view('livewire.admin.profile-manager')
            ->layout('layouts.admin', ['title' => 'Mi perfil']);
    }
}
