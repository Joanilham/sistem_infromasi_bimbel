@props(['name', 'value' => '', 'placeholder' => 'Tulis di sini...'])

<div x-data="quillEditor()" x-init="initQuill($refs.editor, $refs.input)" class="mb-2">
    <input type="hidden" name="{{ $name }}" x-ref="input" value="{{ $value }}">
    <div x-ref="editor" class="bg-white dark:bg-zinc-800 rounded-b-lg border-x border-b border-slate-200 dark:border-zinc-700 min-h-[150px]">{!! $value !!}</div>
</div>

@once
@push('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<style>
.ql-toolbar { border-radius: 0.5rem 0.5rem 0 0; background: #F8FAFC; border-color: #E2E8F0; }
.dark .ql-toolbar { background: #18181B; border-color: #3F3F46; }
.dark .ql-snow .ql-stroke { stroke: #A1A1AA; }
.dark .ql-snow .ql-fill, .dark .ql-snow .ql-stroke.ql-fill { fill: #A1A1AA; }
.dark .ql-snow .ql-picker { color: #A1A1AA; }
.ql-container { border-radius: 0 0 0.5rem 0.5rem; font-family: inherit; font-size: 1rem; border-color: #E2E8F0; }
.dark .ql-container { border-color: #3F3F46; color: #E4E4E7; }
</style>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('quillEditor', () => ({
        initQuill(editorRef, inputRef) {
            const quill = new Quill(editorRef, {
                theme: 'snow',
                placeholder: '{{ $placeholder }}',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'header': [1, 2, 3, false] }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['clean']
                    ]
                }
            });
            
            // Sync with hidden input
            quill.on('text-change', () => {
                let html = quill.root.innerHTML;
                if(html === '<p><br></p>') html = '';
                inputRef.value = html;
            });
            
            // Format mathjax on load
            if (window.MathJax) {
                MathJax.typesetPromise([editorRef]).catch((err) => console.log(err));
            }
        }
    }));
});
</script>
@endpush
@endonce
