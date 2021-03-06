var PostPage = (function() {
    var STATE_DRAFT = 1;
    var STATE_SCHEDULED = 2;
    var STATE_PUBLISHED = 3;

    var options;

    function init(opts) {
        options = opts;
        initUploader();
        initEditor();
        initSaveState();
        initSlug();
        initGoogleView();
    }

    function initUploader() {
        var el = document.querySelector('[data-js=upload-thumbnail]');
        new ImageUploader(el, {
            sortable: false,
            cropper: true,
            deletable: false,
            cropperOptions: {
                aspectRatio: 500 / 200,
            },
            service: new AjaxService(el.dataset.url, document.querySelector('[name=thumbnail]')),
            max: 1,
        });

        el = document.querySelector('[data-js=upload-image]');
        new ImageUploader(el, {
            sortable: false,
            cropper: true,
            deletable: false,
            cropperOptions: {
                aspectRatio: 1920 / 300,
            },
            service: new AjaxService(el.dataset.url, document.querySelector('[name=image]')),
            max: 1,
        });
    }

    function initEditor() {
        $('[name=content]').froalaEditor({
            language: 'fr',
            imageUploadURL: options.imageUploadURL,
            toolbarButtons: ['paragraphFormat', 'bold', 'italic', 'underline', 'formatUL', 'align', '|', 'insertLink', 'insertTable', 'insertImage', '|', 'outdent', 'indent', 'insertTable', '|', 'fullscreen', 'undo', 'redo', 'clearFormatting'],
            height: 300,
            heightMax: 500
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

        slug.defaults.mode = 'rfc3986';
        slugButton.addEventListener('click', function(event) {
            event.preventDefault();
            slugInput.value = slug(titleInput.value);
        });

        if (!slugInput.value) {
            titleInput.addEventListener('change', function() {
                slugInput.value = slug(titleInput.value);
            });
        }
    }

    function initGoogleView() {
        var slugInput = document.querySelector('[name=slug]');
        var titleInput = document.querySelector('[name=seo_title]');
        var descriptionInput = document.querySelector('[name=seo_description]');

        var urlLabel = document.querySelector('[data-js=url]');
        var titleLabel = document.querySelector('[data-js=title]');
        var descriptionLabel = document.querySelector('[data-js=description]');

        urlLabel.innerHTML = slugInput.value;
        titleLabel.innerHTML = titleInput.value;
        descriptionLabel.innerHTML = descriptionInput.value;

        slugInput.addEventListener('change', function() {
            urlLabel.innerHTML = slugInput.value;
        });

        titleInput.addEventListener('change', function() {
            titleLabel.innerHTML = titleInput.value;
        });

        descriptionInput.addEventListener('change', function() {
            descriptionLabel.innerHTML = descriptionInput.value;
        });
    }

    function togglePublishedDate(state) {
        var publishedInput = document.querySelector('.js-published_at');

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

    return init;
})();
