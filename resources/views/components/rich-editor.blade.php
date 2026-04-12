@props(['id' => 'content', 'model' => 'content'])

<textarea
    id="{{ $id }}"
    wire:model="{{ $model }}"
    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
    rows="6"
></textarea>

<script>
    function initRichEditor_{{ $id }}() {
        if (typeof tinymce === 'undefined') return;

        tinymce.remove('#{{ $id }}');

        tinymce.init({
            selector: '#{{ $id }}',
            base_url: '/build/tinymce',
            suffix: '.min',
            height: 380,
            menubar: false,
            plugins: 'link lists image media table code fullscreen wordcount',
            toolbar:
                'undo redo | blocks | bold italic underline | ' +
                'alignleft aligncenter alignright | ' +
                'bullist numlist | link image | table | code fullscreen',
            promotion: false,
            branding: false,
            setup(editor) {
                editor.on('Change KeyUp', function () {
                    editor.save();
                    document.getElementById('{{ $id }}').dispatchEvent(new Event('input'));
                });

                Livewire.on('resetEditor', () => {
                    editor.setContent('');
                });
            },
        });
    }

    // Ejecutar cuando el elemento está en el DOM
    document.addEventListener('DOMContentLoaded', initRichEditor_{{ $id }});

    // Para cuando Livewire re-renderiza
    document.addEventListener('livewire:update', function () {
        if (document.getElementById('{{ $id }}')) {
            setTimeout(() => initRichEditor_{{ $id }}(), 50);
        }
    });
</script>
