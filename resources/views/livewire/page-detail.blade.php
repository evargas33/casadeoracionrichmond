<div>
    @includeFirst([
        'livewire.page-templates.' . $page->template,
        'livewire.page-templates.default',
    ], ['page' => $page, 'settings' => $settings])
</div>
