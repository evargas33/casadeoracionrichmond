import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import tinymce from 'tinymce';

import 'tinymce/themes/silver';
import 'tinymce/icons/default';
import 'tinymce/models/dom';


//plugins

import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/image';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/wordcount';

window.initTinyMCE = function (selector) {
    tinymce.init({
        selector: selector,
        base_url: '/build/tinymce',
        suffix: '.min',
        height: 400,
        menubar: false,
        plugins: 'link lists image media table code  fullscreen wordcount',
        toolbar:
            'undo redo | formatselect | bold italic underline |' +
            'alignleft aligntcente alignright | ' +
            'bullist numlist | link image media | table | code fullscreen',
        content_css: false,
        skin: 'oxide',
        promotion: false,
        branding: false,
        setup(editor) {
            //sync with Livewire al cambiar contenido
            editor.on('Change KeyUp', function(){
                editor.save();
                const el = document.querySelector(selector);
                if (el) {
                    el.dispatchEvent(new Event('input'));
                }
            });
        },
    });
};

window.destroyTinyMCE = function (selector) {
    const editor = tinymce.get(selector.replace('#', ''));
    if (editor) editor.remove();
};




