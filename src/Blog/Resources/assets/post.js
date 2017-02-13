(function() {
    var STATE_DRAFT = 1;
    var STATE_SCHEDULED = 2;
    var STATE_PUBLISHED = 3;

    function init() {
        initUploader();
        initEditor();
        initSaveState();
        initSlug();
    }

    function initUploader() {
        var el = document.querySelector('[data-js=upload-thumbnail]');
        new ImageUploader(el, {
            cropper: true,
            cropperOptions: {
                aspectRatio: 500 / 200,
            },
            service: new AjaxService(el.dataset.url),
        });

        el = document.querySelector('[data-js=upload-image]');
        new ImageUploader(el, {
            cropper: true,
            cropperOptions: {
                aspectRatio: 1000 / 300,
            },
            service: new AjaxService(el.dataset.url),
        });
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

    function initSlug() {
        var titleInput = document.querySelector('[name=title]');
        var slugInput = document.querySelector('[name=slug]');
        var slugButton = document.querySelector('[data-js=generate-slug]');

        slugButton.addEventListener('click', function(event) {
            event.preventDefault();
            slugInput.value = slugify(titleInput.value);
        });

        if (!slugInput.value) {
            titleInput.addEventListener('change', function() {
                slugInput.value = slugify(titleInput.value);
            });
        }
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

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\-]+/g, '') // Remove all non-word chars
            .replace(/\-\-+/g, '-') // Replace multiple - with single -
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, ''); // Trim - from end of text
    }

    init();
})();
