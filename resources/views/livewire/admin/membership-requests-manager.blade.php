<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Solicitudes de Membresía</h1>
        <button wire:click="exportCsv"
            class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Exportar CSV
        </button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <input wire:model.live.debounce.300ms="search"
            type="text" placeholder="Buscar por nombre, email o teléfono..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

        <select wire:model.live="filterStatus"
            class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Todos los estados</option>
            <option value="pending">Pendientes</option>
            <option value="approved">Aprobadas</option>
            <option value="rejected">Rechazadas</option>
        </select>
    </div>

    {{-- Stats bar --}}
    @if($requests->total() > 0)
        <div class="flex items-center gap-6 mb-4 text-sm text-gray-500">
            <span>
                <span class="font-semibold text-gray-700">{{ $requests->total() }}</span>
                {{ $requests->total() === 1 ? 'solicitud' : 'solicitudes' }}
            </span>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Nombre</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Teléfono</th>
                    <th class="px-5 py-3 text-left">Estado</th>
                    <th class="px-5 py-3 text-left">Fecha Solicitud</th>
                    <th class="px-5 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-900 font-medium">{{ e($req->full_name) }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ e($req->email) }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ e($req->phone) }}</td>
                        <td class="px-5 py-3">
                            @if ($req->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @elseif ($req->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aprobada
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rechazada
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $req->submission_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <button wire:click="viewDetail({{ $req->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                    Ver
                                </button>
                                @if ($req->status === 'pending')
                                    <button wire:click="approve({{ $req->id }})"
                                        class="text-green-600 hover:text-green-800 text-xs font-medium">
                                        Aprobar
                                    </button>
                                    <button wire:click="openRejectModal({{ $req->id }})"
                                        class="text-red-600 hover:text-red-800 text-xs font-medium">
                                        Rechazar
                                    </button>
                                @endif
                                @if (auth()->user()->hasRole('superadmin'))
                                    <button wire:click="delete({{ $req->id }})"
                                        wire:confirm="¿Eliminar esta solicitud?"
                                        class="text-gray-600 hover:text-gray-800 text-xs font-medium">
                                        Eliminar
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                            No hay solicitudes de membresía
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $requests->links() }}
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
            @if ($selectedRequest)
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $selectedRequest->full_name }}</h2>
                    <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 font-medium">Email</p>
                            <p class="text-gray-900">{{ $selectedRequest->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Teléfono</p>
                            <p class="text-gray-900">{{ $selectedRequest->phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Domicilio</p>
                            <p class="text-gray-900">{{ $selectedRequest->address }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Ciudad</p>
                            <p class="text-gray-900">{{ $selectedRequest->city }}, {{ $selectedRequest->zip_code }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Fecha de Nacimiento</p>
                            <p class="text-gray-900">{{ $selectedRequest->birth_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium">Estado Civil</p>
                            <p class="text-gray-900">{{ ucfirst($selectedRequest->marital_status) }}</p>
                        </div>
                    </div>

                    @if ($selectedRequest->marital_status === 'casado' && $selectedRequest->spouse_name)
                        <div>
                            <p class="text-gray-500 font-medium">Esposo/a</p>
                            <p class="text-gray-900">{{ $selectedRequest->spouse_name }}</p>
                        </div>
                    @endif

                    @if ($selectedRequest->has_children)
                        <div>
                            <p class="text-gray-500 font-medium">Hijos</p>
                            <p class="text-gray-900">{{ $selectedRequest->children_names }}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-gray-500 font-medium mb-2">Información Espiritual</p>
                        <div class="space-y-1 text-sm">
                            <p>Recibió a Jesús: {{ $selectedRequest->received_jesus ? '✓ Sí' : '✗ No' }}</p>
                            <p>Bautizado en agua: {{ $selectedRequest->baptized_water ? '✓ Sí' : '✗ No' }}</p>
                            @if ($selectedRequest->baptism_church)
                                <p>Iglesia de bautismo: {{ $selectedRequest->baptism_church }}</p>
                            @endif
                            <p>Ha servido en ministerio: {{ $selectedRequest->has_served_ministry ? '✓ Sí' : '✗ No' }}</p>
                            <p>Desea servir en ministerio: {{ $selectedRequest->wants_serve_ministry ? '✓ Sí' : '✗ No' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-gray-500 font-medium mb-2">Contacto de Emergencia</p>
                        <p><strong>Nombre:</strong> {{ $selectedRequest->emergency_contact_name }}</p>
                        <p><strong>Teléfono:</strong> {{ $selectedRequest->emergency_contact_phone }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-gray-500 font-medium">Compromiso Aceptado</p>
                        <p class="text-gray-900">{{ $selectedRequest->commitment_accepted ? '✓ Sí' : '✗ No' }}</p>
                    </div>

                    @if ($selectedRequest->status === 'rejected' && $selectedRequest->rejection_reason)
                        <div class="border-t border-gray-200 pt-4 bg-red-50 p-3 rounded">
                            <p class="text-gray-500 font-medium">Motivo de Rechazo</p>
                            <p class="text-gray-900">{{ e($selectedRequest->rejection_reason) }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Reject Modal --}}
    @if($showRejectModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Rechazar Solicitud</h2>
                <button wire:click="closeRejectModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Motivo del Rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="rejection_reason"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('rejection_reason') border-red-500 @enderror"
                        placeholder="Explica brevemente el motivo del rechazo..." rows="4"></textarea>
                    @error('rejection_reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button wire:click="closeRejectModal"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                    <button wire:click="reject"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Rechazar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
