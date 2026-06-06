<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Find all forms that do NOT have the .no-autosave class
        const forms = document.querySelectorAll('form:not(.no-autosave)');

        forms.forEach(form => {
            // Use the form's action URL or current pathname as a unique key prefix
            // If action is empty, fallback to window.location.pathname
            const actionPath = form.getAttribute('action') ? new URL(form.action, window.location.origin).pathname : window.location.pathname;
            const formKeyPrefix = 'autosave_' + actionPath;

            // Load saved data when the page loads
            loadFormData(form, formKeyPrefix);

            // Listen to input changes and save them
            form.addEventListener('input', function (e) {
                const target = e.target;
                if (shouldSave(target)) {
                    saveFieldData(target, formKeyPrefix);
                }
            });
            form.addEventListener('change', function (e) {
                const target = e.target;
                if (shouldSave(target)) {
                    saveFieldData(target, formKeyPrefix);
                }
            });

            // Clear the saved data when the form is submitted
            form.addEventListener('submit', function () {
                clearFormData(form, formKeyPrefix);
            });
        });

        function shouldSave(el) {
            // Don't save empty names, passwords, hidden fields, files, or specific ignored inputs
            if (!el.name) return false;
            if (el.type === 'password' || el.type === 'hidden' || el.type === 'file' || el.type === 'submit' || el.type === 'button') return false;
            if (el.dataset.noAutosave !== undefined) return false;
            return true;
        }

        function saveFieldData(el, prefix) {
            const key = prefix + '_' + el.name;
            let value = el.value;

            if (el.type === 'checkbox') {
                // If it's a checkbox array (e.g. name="hobbies[]")
                if (el.name.endsWith('[]')) {
                    const checkboxes = document.querySelectorAll(`input[name="${el.name}"]`);
                    const checkedValues = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
                    value = JSON.stringify(checkedValues);
                } else {
                    value = el.checked ? 'true' : 'false';
                }
            } else if (el.type === 'radio') {
                value = el.value;
            } else if (el.tagName === 'SELECT' && el.multiple) {
                const selected = Array.from(el.selectedOptions).map(opt => opt.value);
                value = JSON.stringify(selected);
            }

            try {
                localStorage.setItem(key, value);
            } catch (e) {
                console.warn('LocalStorage quota exceeded or disabled.', e);
            }
        }

        function loadFormData(form, prefix) {
            const elements = form.elements;
            for (let i = 0; i < elements.length; i++) {
                const el = elements[i];
                if (!shouldSave(el)) continue;

                const key = prefix + '_' + el.name;
                const savedValue = localStorage.getItem(key);

                if (savedValue !== null) {
                    if (el.type === 'checkbox') {
                        if (el.name.endsWith('[]')) {
                            try {
                                const arr = JSON.parse(savedValue);
                                if (Array.isArray(arr) && arr.includes(el.value)) {
                                    el.checked = true;
                                }
                            } catch (e) {}
                        } else {
                            el.checked = savedValue === 'true';
                        }
                    } else if (el.type === 'radio') {
                        if (el.value === savedValue) {
                            el.checked = true;
                        }
                    } else if (el.tagName === 'SELECT' && el.multiple) {
                        try {
                            const arr = JSON.parse(savedValue);
                            if (Array.isArray(arr)) {
                                Array.from(el.options).forEach(opt => {
                                    if (arr.includes(opt.value)) opt.selected = true;
                                });
                            }
                        } catch (e) {}
                    } else {
                        // For Alpine.js compatibility, we trigger an input event
                        // after changing the value programmatically
                        if (el.value !== savedValue && savedValue !== "") {
                            el.value = savedValue;
                            el.dispatchEvent(new Event('input', { bubbles: true }));
                            el.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                }
            }
        }

        function clearFormData(form, prefix) {
            const elements = form.elements;
            for (let i = 0; i < elements.length; i++) {
                const el = elements[i];
                if (!shouldSave(el)) continue;
                localStorage.removeItem(prefix + '_' + el.name);
            }
        }
    });
</script>
