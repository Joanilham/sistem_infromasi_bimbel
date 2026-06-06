@props(['name', 'value' => '', 'placeholder' => 'Tulis di sini...'])

<div x-data="tinyMceEditor()" x-init="initTinyMce($refs.input)" class="mb-2">
    <textarea name="{{ $name }}" x-ref="input" class="hidden">{!! \App\Helpers\HtmlSanitizer::clean($value) !!}</textarea>
</div>

@once
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<style>
.tox-tinymce { 
    border-radius: 0.5rem !important; 
    border: 1px solid #E2E8F0 !important; 
    box-shadow: none !important;
}
.dark .tox-tinymce { 
    border-color: #3F3F46 !important; 
}
.tox-tinymce--focused {
    border-color: #6366F1 !important;
    box-shadow: 0 0 0 1px #6366F1 !important;
}
.tox-tinymce svg, .tox-tinymce-aux svg { 
    display: inline !important; 
}
.tox .tox-tbtn {
    box-sizing: content-box !important;
}
</style>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tinyMceEditor', () => ({
        initTinyMce(inputRef) {
            const isDark = document.documentElement.classList.contains('dark');
            
            tinymce.init({
                target: inputRef,
                min_height: 400,
                promotion: false,
                branding: false,
                menubar: true,
                toolbar_sticky: false,
                skin: isDark ? 'oxide-dark' : 'oxide',
                content_css: isDark ? 'dark' : 'default',
                placeholder: '{{ $placeholder }}',
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'wordcount', 'emoticons', 'directionality'
                ],
                toolbar1: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | subscript superscript',
                toolbar2: 'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | ltr rtl | link image media table emoticons charmap | removeformat code fullscreen preview',
                setup: function (editor) {
                    editor.on('change keyup', function () {
                        editor.save();
                        inputRef.dispatchEvent(new Event('input', { bubbles: true }));
                    });
                }
            });
            
            // Format mathjax on load for the rest of the page if needed
            if (window.MathJax) {
                setTimeout(() => {
                    MathJax.typesetPromise().catch((err) => console.log(err));
                }, 500);
            }
        }
    }));
});
</script>
@endpush
@endonce
