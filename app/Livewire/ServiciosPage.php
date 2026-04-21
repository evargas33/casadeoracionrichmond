<?php

namespace App\Livewire;

use App\Models\ServicePlan;
use Livewire\Component;

class ServiciosPage extends Component
{
    public function render()
    {
        $plans = ServicePlan::published()
            ->upcoming()
            ->with([
                'songs' => fn ($q) => $q->orderBy('order'),
                'ushers',
                'technicians',
            ])
            ->orderBy('date')
            ->get();

        return view('livewire.servidores-page', [
            'plans' => $plans,
        ])->layout('layouts.public', ['pageTitle' => 'Área de Servidores']);
    }
}
