<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class PagesManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterPublished = '';

    protected $listeners = ['mediaSelected' => 'onMediaSelected'];

    public bool $showModal = false;
    public bool $isEditing = false;

    public ?int $page_id = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public ?int $category_id = null;
    public string $meta_title = '';
    public string $meta_description = '';
    public string $og_image = '';
    public bool $published = false;
    public bool $in_menu = false;
    public int $order = 0;
    public string $template = 'default';

    protected function rules(): array
    {
        return [
            'title'            => 'required|string|max:200',
            'slug'             => 'required|string|max:210',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'og_image'         => 'nullable|url|max:255',
            'published'        => 'boolean',
            'in_menu'          => 'boolean',
            'order'            => 'integer|min:0',
            'template'         => 'required|string|in:default,contact,about,visit-us',
        ];
    }

    public function updatedTitle(): void
    {
        if (! $this->isEditing) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
        $this->dispatch('open-editor');
    }

    public function openEdit(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $page = Page::findOrFail($id);

        $this->page_id          = $page->id;
        $this->title            = $page->title;
        $this->slug             = $page->slug;
        $this->content          = $page->content;
        $this->category_id      = $page->category_id;
        $this->meta_title       = $page->meta_title ?? '';
        $this->meta_description = $page->meta_description ?? '';
        $this->og_image         = $page->og_image ?? '';
        $this->published        = $page->published;
        $this->in_menu          = $page->in_menu;
        $this->order            = $page->order;
        $this->template         = $page->template;

        $this->isEditing = true;
        $this->showModal = true;
        $this->dispatch('open-editor');
    }

    public function save(): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $data = $this->validate();
        $data['user_id'] = auth()->id();

        if ($this->isEditing) {
            Page::findOrFail($this->page_id)->update($data);
            session()->flash('success', 'Page updated successfully.');
        } else {
            Page::create($data);
            session()->flash('success', 'Page created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function togglePublished(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        $page = Page::findOrFail($id);
        $page->update(['published' => ! $page->published]);
    }

    public function delete(int $id): void
    {
        abort_if(! auth()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        Page::findOrFail($id)->delete();
        session()->flash('success', 'Page deleted.');
    }

    public function onMediaSelected(string $field, string $url): void
    {
        if (property_exists($this, $field)) {
            $this->$field = $url;
        }
    }

    public function closeModal(): void
    {
        $this->dispatch('close-editor');
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('resetEditor');
    }

    private function resetForm(): void
    {
        $this->page_id          = null;
        $this->title            = '';
        $this->slug             = '';
        $this->content          = '';
        $this->category_id      = null;
        $this->meta_title       = '';
        $this->meta_description = '';
        $this->og_image         = '';
        $this->published        = false;
        $this->in_menu          = false;
        $this->order            = 0;
        $this->template         = 'default';
        $this->resetValidation();
    }

    public function render()
    {
        $pages = Page::query()
            ->with(['author', 'category'])
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('slug', 'like', "%{$this->search}%")
            )
            ->when($this->filterPublished !== '', fn ($q) =>
                $q->where('published', (bool) $this->filterPublished)
            )
            ->orderBy('order')
            ->orderBy('title')
            ->paginate(10);

        $categories = Category::forPages()->orderBy('name')->get();

        return view('livewire.admin.pages-manager', [
            'pages'      => $pages,
            'categories' => $categories,
        ])->layout('layouts.admin', ['title' => 'Pages']);
    }
}
