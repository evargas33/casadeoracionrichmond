<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesManager extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $filterType = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int  $category_id = null;
    public string $name        = '';
    public string $slug        = '';
    public string $type        = 'event';
    public string $description = '';
    public string $color       = '#1a2e4a';
    public bool   $active      = true;

    public function updatingSearch(): void { $this->resetPage(); }

    public function updatedName(): void
    {
        if (! $this->isEditing) {
            $this->slug = Str::slug($this->name);
        }
    }

    protected function rules(): array
    {
        $uniqueSlug = $this->isEditing
            ? 'required|string|max:110|unique:categories,slug,' . $this->category_id
            : 'required|string|max:110|unique:categories,slug';

        return [
            'name'        => 'required|string|max:100',
            'slug'        => $uniqueSlug,
            'type'        => 'required|in:event,page',
            'description' => 'nullable|string',
            'color'       => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'active'      => 'boolean',
        ];
    }

    public function openCreate(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $cat = Category::findOrFail($id);

        $this->category_id = $cat->id;
        $this->name        = $cat->name;
        $this->slug        = $cat->slug;
        $this->type        = $cat->type;
        $this->description = $cat->description ?? '';
        $this->color       = $cat->color;
        $this->active      = $cat->active;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $data = $this->validate();

        if ($this->isEditing) {
            Category::findOrFail($this->category_id)->update($data);
            session()->flash('success', 'Categoría actualizada.');
        } else {
            Category::create($data);
            session()->flash('success', 'Categoría creada.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $cat = Category::findOrFail($id);
        $cat->update(['active' => ! $cat->active]);
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $cat = Category::withCount(['events', 'pages'])->findOrFail($id);

        if ($cat->events_count > 0 || $cat->pages_count > 0) {
            session()->flash('error', "No se puede eliminar: tiene {$cat->events_count} evento(s) y {$cat->pages_count} página(s) asociados.");
            return;
        }

        $cat->delete();
        session()->flash('success', 'Categoría eliminada.');
    }

    private function resetForm(): void
    {
        $this->category_id = null;
        $this->name        = '';
        $this->slug        = '';
        $this->type        = 'event';
        $this->description = '';
        $this->color       = '#1a2e4a';
        $this->active      = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::query()
            ->withCount(['events', 'pages'])
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
            )
            ->when($this->filterType, fn ($q) =>
                $q->where('type', $this->filterType)
            )
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.categories-manager', compact('categories'))
            ->layout('layouts.admin', ['title' => 'Categorías']);
    }
}
