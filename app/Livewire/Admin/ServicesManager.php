<?php

namespace App\Livewire\Admin;

use App\Models\ServicePlan;
use App\Models\ServiceSong;
use App\Models\ServiceUsher;
use App\Models\ServiceTechnician;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ServicesManager extends Component
{
    use WithFileUploads, WithPagination;

    // ─────── List state ───────
    public string $search       = '';
    public string $filterStatus = '';
    public string $filterType   = '';

    // ─────── View mode ───────
    public string $currentView = 'list'; // 'list' | 'detail'
    public string $activeTab   = 'general';
    public bool   $isEditing   = false;

    // ─────── Service plan fields ───────
    public ?int  $plan_id           = null;
    public string $plan_date         = '';
    public string $plan_title        = 'Servicio Dominical';
    public string $plan_service_type = 'domingo';
    public string $plan_status       = 'borrador';

    // ─────── Predicación ───────
    public string $sermon_topic          = '';
    public string $bible_passage         = '';
    public string $sermon_notes_path     = '';
    public string $bible_citations_path  = '';
    public $sermon_notes_file    = null;
    public $bible_citations_file = null;

    // ─────── Alabanza ───────
    public string $worship_uniform_color = '';
    public string $worship_uniform_notes = '';

    // ─────── Ujieres ───────
    public string $usher_uniform_color = '';
    public string $usher_uniform_notes = '';

    // ─────── Loaded sub-items ───────
    public array $songs        = [];
    public array $ushers       = [];
    public array $technicians  = [];

    // ─────── Song inline form ───────
    public bool   $showSongForm     = false;
    public ?int   $editing_song_id  = null;
    public string $song_title       = '';
    public string $song_artist      = '';
    public string $song_key         = '';
    public int    $song_order       = 1;
    public string $song_notes       = '';
    public $song_onsong_file        = null;
    public string $song_onsong_path = '';
    public $song_pdf_file           = null;
    public string $song_pdf_path    = '';

    // ─────── Usher inline form ───────
    public bool   $showUsherForm      = false;
    public ?int   $editing_usher_id   = null;
    public string $usher_name         = '';
    public ?int   $usher_user_id      = null;
    public string $usher_assignment   = 'general';
    public string $usher_notes        = '';

    // ─────── Technician inline form ───────
    public bool   $showTechForm    = false;
    public ?int   $editing_tech_id = null;
    public string $tech_name       = '';
    public ?int   $tech_user_id    = null;
    public string $tech_position   = 'apoyo';
    public string $tech_notes      = '';

    // ─────── Role helpers ───────

    public function isAdmin(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }

    public function canEditTab(string $tab): bool
    {
        $user = auth()->user();
        return match ($tab) {
            'general'    => $user->hasAnyRole(['superadmin', 'admin']),
            'predicacion' => $user->hasAnyRole(['superadmin', 'admin', 'pastor']),
            'alabanza'   => $user->hasAnyRole(['superadmin', 'admin', 'lider_alabanza']),
            'ujieres'    => $user->hasAnyRole(['superadmin', 'admin', 'lider_ujieres']),
            'tecnicos'   => $user->hasAnyRole(['superadmin', 'admin', 'lider_tecnicos']),
            default      => false,
        };
    }

    // ─────── List actions ───────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        abort_if(! $this->isAdmin(), 403);
        $this->resetPlanForm();
        $this->isEditing = false;
        $this->activeTab = 'general';
        $this->plan_date = now()->format('Y-m-d');
        $this->currentView = 'detail';
    }

    public function openEdit(int $id): void
    {
        abort_if(
            ! auth()->user()->hasAnyRole(['superadmin', 'admin', 'pastor', 'lider_alabanza', 'lider_ujieres', 'lider_tecnicos']),
            403
        );

        $plan = ServicePlan::findOrFail($id);

        $this->plan_id           = $plan->id;
        $this->plan_date         = $plan->date->format('Y-m-d');
        $this->plan_title        = $plan->title;
        $this->plan_service_type = $plan->service_type;
        $this->plan_status       = $plan->status;

        $this->sermon_topic         = $plan->sermon_topic ?? '';
        $this->bible_passage        = $plan->bible_passage ?? '';
        $this->sermon_notes_path    = $plan->sermon_notes_path ?? '';
        $this->bible_citations_path = $plan->bible_citations_path ?? '';

        $this->worship_uniform_color = $plan->worship_uniform_color ?? '';
        $this->worship_uniform_notes = $plan->worship_uniform_notes ?? '';

        $this->usher_uniform_color = $plan->usher_uniform_color ?? '';
        $this->usher_uniform_notes = $plan->usher_uniform_notes ?? '';

        $this->loadSubItems();

        $this->isEditing = true;

        // Open the tab relevant to the user's role
        $user = auth()->user();
        if ($user->hasAnyRole(['superadmin', 'admin'])) {
            $this->activeTab = 'general';
        } elseif ($user->hasRole('pastor')) {
            $this->activeTab = 'predicacion';
        } elseif ($user->hasRole('lider_alabanza')) {
            $this->activeTab = 'alabanza';
        } elseif ($user->hasRole('lider_ujieres')) {
            $this->activeTab = 'ujieres';
        } elseif ($user->hasRole('lider_tecnicos')) {
            $this->activeTab = 'tecnicos';
        }

        $this->currentView = 'detail';
    }

    public function backToList(): void
    {
        $this->currentView = 'list';
        $this->resetPlanForm();
    }

    public function toggleStatus(int $id): void
    {
        abort_if(! $this->isAdmin(), 403);
        $plan = ServicePlan::findOrFail($id);
        $plan->update(['status' => $plan->status === 'publicado' ? 'borrador' : 'publicado']);
    }

    public function delete(int $id): void
    {
        abort_if(! $this->isAdmin(), 403);

        $plan = ServicePlan::with('songs')->findOrFail($id);

        foreach ($plan->songs as $song) {
            if ($song->onsong_path) Storage::disk('public')->delete($song->onsong_path);
            if ($song->pdf_path)    Storage::disk('public')->delete($song->pdf_path);
        }
        if ($plan->sermon_notes_path)    Storage::disk('public')->delete($plan->sermon_notes_path);
        if ($plan->bible_citations_path) Storage::disk('public')->delete($plan->bible_citations_path);

        $plan->delete();
        session()->flash('success', 'Servicio eliminado.');
    }

    // ─────── Per-tab saves ───────

    public function saveGeneral(): void
    {
        abort_if(! $this->isAdmin(), 403);

        $this->validate([
            'plan_date'         => 'required|date',
            'plan_title'        => 'required|string|max:200',
            'plan_service_type' => 'required|in:domingo,sabado,viernes,especial',
            'plan_status'       => 'required|in:borrador,publicado',
        ]);

        $data = [
            'date'         => $this->plan_date,
            'title'        => $this->plan_title,
            'service_type' => $this->plan_service_type,
            'status'       => $this->plan_status,
        ];

        if ($this->isEditing) {
            ServicePlan::findOrFail($this->plan_id)->update($data);
        } else {
            $plan = ServicePlan::create($data);
            $this->plan_id   = $plan->id;
            $this->isEditing = true;
        }

        session()->flash('tab_success', 'Información general guardada.');
    }

    public function savePredicacion(): void
    {
        abort_if(! $this->canEditTab('predicacion'), 403);
        abort_if(! $this->plan_id, 422);

        $this->validate([
            'sermon_topic'       => 'nullable|string|max:300',
            'bible_passage'      => 'nullable|string|max:200',
            'sermon_notes_file'  => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'bible_citations_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $data = [
            'sermon_topic'  => $this->sermon_topic  ?: null,
            'bible_passage' => $this->bible_passage ?: null,
        ];

        if ($this->sermon_notes_file) {
            if ($this->sermon_notes_path) Storage::disk('public')->delete($this->sermon_notes_path);
            $ext  = $this->sermon_notes_file->getClientOriginalExtension();
            $path = $this->sermon_notes_file->storeAs('service-files', Str::uuid() . '.' . $ext, 'public');
            $data['sermon_notes_path'] = $path;
            $this->sermon_notes_path   = $path;
            $this->sermon_notes_file   = null;
        }

        if ($this->bible_citations_file) {
            if ($this->bible_citations_path) Storage::disk('public')->delete($this->bible_citations_path);
            $ext  = $this->bible_citations_file->getClientOriginalExtension();
            $path = $this->bible_citations_file->storeAs('service-files', Str::uuid() . '.' . $ext, 'public');
            $data['bible_citations_path'] = $path;
            $this->bible_citations_path   = $path;
            $this->bible_citations_file   = null;
        }

        ServicePlan::findOrFail($this->plan_id)->update($data);
        session()->flash('tab_success', 'Predicación guardada.');
    }

    public function removeSermonNotesFile(): void
    {
        abort_if(! $this->canEditTab('predicacion'), 403);
        if ($this->sermon_notes_path && $this->plan_id) {
            Storage::disk('public')->delete($this->sermon_notes_path);
            ServicePlan::findOrFail($this->plan_id)->update(['sermon_notes_path' => null]);
            $this->sermon_notes_path = '';
        }
    }

    public function removeBibleCitationsFile(): void
    {
        abort_if(! $this->canEditTab('predicacion'), 403);
        if ($this->bible_citations_path && $this->plan_id) {
            Storage::disk('public')->delete($this->bible_citations_path);
            ServicePlan::findOrFail($this->plan_id)->update(['bible_citations_path' => null]);
            $this->bible_citations_path = '';
        }
    }

    public function saveAlabanza(): void
    {
        abort_if(! $this->canEditTab('alabanza'), 403);
        abort_if(! $this->plan_id, 422);

        $this->validate([
            'worship_uniform_color' => 'nullable|string|max:100',
            'worship_uniform_notes' => 'nullable|string|max:500',
        ]);

        ServicePlan::findOrFail($this->plan_id)->update([
            'worship_uniform_color' => $this->worship_uniform_color ?: null,
            'worship_uniform_notes' => $this->worship_uniform_notes ?: null,
        ]);

        session()->flash('tab_success', 'Alabanza guardada.');
    }

    public function saveUjieres(): void
    {
        abort_if(! $this->canEditTab('ujieres'), 403);
        abort_if(! $this->plan_id, 422);

        $this->validate([
            'usher_uniform_color' => 'nullable|string|max:100',
            'usher_uniform_notes' => 'nullable|string|max:500',
        ]);

        ServicePlan::findOrFail($this->plan_id)->update([
            'usher_uniform_color' => $this->usher_uniform_color ?: null,
            'usher_uniform_notes' => $this->usher_uniform_notes ?: null,
        ]);

        session()->flash('tab_success', 'Ujieres guardado.');
    }

    // ─────── Songs ───────

    public function openSongForm(?int $id = null): void
    {
        abort_if(! $this->canEditTab('alabanza'), 403);
        $this->resetSongForm();

        if ($id) {
            $song = ServiceSong::findOrFail($id);
            $this->editing_song_id  = $song->id;
            $this->song_title       = $song->title;
            $this->song_artist      = $song->artist ?? '';
            $this->song_key         = $song->song_key ?? '';
            $this->song_order       = $song->order;
            $this->song_notes       = $song->notes ?? '';
            $this->song_onsong_path = $song->onsong_path ?? '';
            $this->song_pdf_path    = $song->pdf_path ?? '';
        }

        $this->showSongForm = true;
    }

    public function saveSong(): void
    {
        abort_if(! $this->canEditTab('alabanza'), 403);
        abort_if(! $this->plan_id, 422);

        $this->validate([
            'song_title'       => 'required|string|max:200',
            'song_artist'      => 'nullable|string|max:200',
            'song_key'         => 'nullable|string|max:10',
            'song_order'       => 'integer|min:0|max:255',
            'song_notes'       => 'nullable|string|max:500',
            'song_onsong_file' => 'nullable|file|max:5120',
            'song_pdf_file'    => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'service_plan_id' => $this->plan_id,
            'title'           => $this->song_title,
            'artist'          => $this->song_artist ?: null,
            'song_key'        => $this->song_key    ?: null,
            'order'           => $this->song_order,
            'notes'           => $this->song_notes  ?: null,
        ];

        if ($this->song_onsong_file) {
            if ($this->song_onsong_path) Storage::disk('public')->delete($this->song_onsong_path);
            $ext  = $this->song_onsong_file->getClientOriginalExtension();
            $data['onsong_path'] = $this->song_onsong_file->storeAs(
                'service-files', Str::uuid() . ($ext ? '.' . $ext : ''), 'public'
            );
        }

        if ($this->song_pdf_file) {
            if ($this->song_pdf_path) Storage::disk('public')->delete($this->song_pdf_path);
            $data['pdf_path'] = $this->song_pdf_file->storeAs(
                'service-files', Str::uuid() . '.pdf', 'public'
            );
        }

        if ($this->editing_song_id) {
            ServiceSong::findOrFail($this->editing_song_id)->update($data);
        } else {
            ServiceSong::create($data);
        }

        $this->loadSubItems();
        $this->showSongForm = false;
        $this->resetSongForm();
    }

    public function removeSongOnsong(int $id): void
    {
        abort_if(! $this->canEditTab('alabanza'), 403);
        $song = ServiceSong::findOrFail($id);
        if ($song->onsong_path) Storage::disk('public')->delete($song->onsong_path);
        $song->update(['onsong_path' => null]);
        $this->loadSubItems();
        if ($this->editing_song_id === $id) $this->song_onsong_path = '';
    }

    public function removeSongPdf(int $id): void
    {
        abort_if(! $this->canEditTab('alabanza'), 403);
        $song = ServiceSong::findOrFail($id);
        if ($song->pdf_path) Storage::disk('public')->delete($song->pdf_path);
        $song->update(['pdf_path' => null]);
        $this->loadSubItems();
        if ($this->editing_song_id === $id) $this->song_pdf_path = '';
    }

    public function deleteSong(int $id): void
    {
        abort_if(! $this->canEditTab('alabanza'), 403);
        $song = ServiceSong::findOrFail($id);
        if ($song->onsong_path) Storage::disk('public')->delete($song->onsong_path);
        if ($song->pdf_path)    Storage::disk('public')->delete($song->pdf_path);
        $song->delete();
        $this->loadSubItems();
    }

    // ─────── Ushers ───────

    public function openUsherForm(?int $id = null): void
    {
        abort_if(! $this->canEditTab('ujieres'), 403);
        $this->resetUsherForm();

        if ($id) {
            $usher = ServiceUsher::findOrFail($id);
            $this->editing_usher_id  = $usher->id;
            $this->usher_name        = $usher->name;
            $this->usher_user_id     = $usher->user_id;
            $this->usher_assignment  = $usher->assignment;
            $this->usher_notes       = $usher->notes ?? '';
        }

        $this->showUsherForm = true;
    }

    public function updatedUsherUserId($value): void
    {
        if ($value) {
            $user = User::find($value);
            if ($user) $this->usher_name = $user->name;
        }
    }

    public function saveUsher(): void
    {
        abort_if(! $this->canEditTab('ujieres'), 403);
        abort_if(! $this->plan_id, 422);

        $this->validate([
            'usher_name'       => 'required|string|max:200',
            'usher_user_id'    => 'nullable|exists:users,id',
            'usher_assignment' => 'required|in:entrada,ofrendas,general,apoyo',
            'usher_notes'      => 'nullable|string|max:300',
        ]);

        $data = [
            'service_plan_id' => $this->plan_id,
            'name'            => $this->usher_name,
            'user_id'         => $this->usher_user_id ?: null,
            'assignment'      => $this->usher_assignment,
            'notes'           => $this->usher_notes ?: null,
        ];

        if ($this->editing_usher_id) {
            ServiceUsher::findOrFail($this->editing_usher_id)->update($data);
        } else {
            ServiceUsher::create($data);
        }

        $this->loadSubItems();
        $this->showUsherForm = false;
        $this->resetUsherForm();
    }

    public function deleteUsher(int $id): void
    {
        abort_if(! $this->canEditTab('ujieres'), 403);
        ServiceUsher::findOrFail($id)->delete();
        $this->loadSubItems();
    }

    // ─────── Technicians ───────

    public function openTechForm(?int $id = null): void
    {
        abort_if(! $this->canEditTab('tecnicos'), 403);
        $this->resetTechForm();

        if ($id) {
            $tech = ServiceTechnician::findOrFail($id);
            $this->editing_tech_id = $tech->id;
            $this->tech_name       = $tech->name;
            $this->tech_user_id    = $tech->user_id;
            $this->tech_position   = $tech->position;
            $this->tech_notes      = $tech->notes ?? '';
        }

        $this->showTechForm = true;
    }

    public function updatedTechUserId($value): void
    {
        if ($value) {
            $user = User::find($value);
            if ($user) $this->tech_name = $user->name;
        }
    }

    public function saveTech(): void
    {
        abort_if(! $this->canEditTab('tecnicos'), 403);
        abort_if(! $this->plan_id, 422);

        $this->validate([
            'tech_name'     => 'required|string|max:200',
            'tech_user_id'  => 'nullable|exists:users,id',
            'tech_position' => 'required|in:mixer,proyeccion,streaming,apoyo',
            'tech_notes'    => 'nullable|string|max:300',
        ]);

        $data = [
            'service_plan_id' => $this->plan_id,
            'name'            => $this->tech_name,
            'user_id'         => $this->tech_user_id ?: null,
            'position'        => $this->tech_position,
            'notes'           => $this->tech_notes ?: null,
        ];

        if ($this->editing_tech_id) {
            ServiceTechnician::findOrFail($this->editing_tech_id)->update($data);
        } else {
            ServiceTechnician::create($data);
        }

        $this->loadSubItems();
        $this->showTechForm = false;
        $this->resetTechForm();
    }

    public function deleteTech(int $id): void
    {
        abort_if(! $this->canEditTab('tecnicos'), 403);
        ServiceTechnician::findOrFail($id)->delete();
        $this->loadSubItems();
    }

    // ─────── Helpers ───────

    private function loadSubItems(): void
    {
        if (! $this->plan_id) return;

        $this->songs = ServiceSong::where('service_plan_id', $this->plan_id)
            ->orderBy('order')->get()->toArray();

        $this->ushers = ServiceUsher::where('service_plan_id', $this->plan_id)
            ->get()->toArray();

        $this->technicians = ServiceTechnician::where('service_plan_id', $this->plan_id)
            ->get()->toArray();
    }

    private function resetPlanForm(): void
    {
        $this->plan_id           = null;
        $this->plan_date         = '';
        $this->plan_title        = 'Servicio Dominical';
        $this->plan_service_type = 'domingo';
        $this->plan_status       = 'borrador';
        $this->sermon_topic          = '';
        $this->bible_passage         = '';
        $this->sermon_notes_path     = '';
        $this->bible_citations_path  = '';
        $this->sermon_notes_file     = null;
        $this->bible_citations_file  = null;
        $this->worship_uniform_color = '';
        $this->worship_uniform_notes = '';
        $this->usher_uniform_color   = '';
        $this->usher_uniform_notes   = '';
        $this->songs       = [];
        $this->ushers      = [];
        $this->technicians = [];
        $this->showSongForm  = false;
        $this->showUsherForm = false;
        $this->showTechForm  = false;
        $this->resetValidation();
    }

    private function resetSongForm(): void
    {
        $this->editing_song_id  = null;
        $this->song_title       = '';
        $this->song_artist      = '';
        $this->song_key         = '';
        $this->song_order       = count($this->songs) + 1;
        $this->song_notes       = '';
        $this->song_onsong_file = null;
        $this->song_onsong_path = '';
        $this->song_pdf_file    = null;
        $this->song_pdf_path    = '';
    }

    private function resetUsherForm(): void
    {
        $this->editing_usher_id = null;
        $this->usher_name       = '';
        $this->usher_user_id    = null;
        $this->usher_assignment = 'general';
        $this->usher_notes      = '';
    }

    private function resetTechForm(): void
    {
        $this->editing_tech_id = null;
        $this->tech_name       = '';
        $this->tech_user_id    = null;
        $this->tech_position   = 'apoyo';
        $this->tech_notes      = '';
    }

    // ─────── Render ───────

    public function render()
    {
        $plans = ServicePlan::query()
            ->withCount(['songs', 'ushers', 'technicians'])
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('sermon_topic', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterType,   fn ($q) => $q->where('service_type', $this->filterType))
            ->orderByDesc('date')
            ->paginate(10);

        $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.services-manager', [
            'plans'             => $plans,
            'users'             => $users,
            'isAdmin'           => $this->isAdmin(),
            'canEditGeneral'    => $this->canEditTab('general'),
            'canEditPredicacion' => $this->canEditTab('predicacion'),
            'canEditAlabanza'   => $this->canEditTab('alabanza'),
            'canEditUjieres'    => $this->canEditTab('ujieres'),
            'canEditTecnicos'   => $this->canEditTab('tecnicos'),
        ])->layout('layouts.admin', ['title' => 'Servicios']);
    }
}
