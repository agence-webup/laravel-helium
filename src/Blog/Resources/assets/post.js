(function() {
    var STATE_DRAFT = 1;
    var STATE_SCHEDULED = 2;
    var STATE_PUBLISHED = 3;

    function init() {
        initEditor();
        initSaveState();
    }

    function initEditor() {
        $('[name=content]').froalaEditor({
            language: 'fr',
            imageUploadURL: "{{ route('admin.image.store') }}?_token={{ csrf_token() }}"
        });
    }

    function initSaveState() {
        var saveButton = document.querySelector('[type=submit]');
        var stateRadios = document.querySelectorAll('[name=state]');
        var stateRadio = document.querySelector('[name=state][checked]');
        var state = stateRadio ? stateRadio.value : null;
        saveButton.innerHTML = getSaveButtonLabel(state);
        togglePublishedDate(state);

        [].forEach.call(stateRadios, function(input) {
            input.addEventListener('change', function(event) {
                saveButton.innerHTML = getSaveButtonLabel(this.value);
                togglePublishedDate(this.value);
            });
        });
    }

    function togglePublishedDate(state) {
        var publishedInput = document.querySelector('[name=published_at]');

        if (state == STATE_SCHEDULED) {
            publishedInput.classList.remove('hidden');
        } else {
            publishedInput.classList.add('hidden');
        }
    }

    function getSaveButtonLabel(state) {
        if (state == STATE_PUBLISHED) {
            return 'Publier';
        } else if (state == STATE_SCHEDULED) {
            return 'Plannifier';
        }

        return 'Enregistrer';
    }

    init();
})();
