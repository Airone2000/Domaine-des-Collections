import $ from 'jquery';
$(() => {


    const $thingForm = $('#thing-form');
    let allowPublish = false;

    let urlParts = window.location.href.split('/')
    urlParts.pop();
    const baseUrl = urlParts.join('/');

    if ($thingForm.length === 1) {
        const $syncingLoader = $thingForm.find('#syncing-loader');
        const $btnPublish = $thingForm.find('button#publish-object');
        const timeouts = {};

        // Enable / Disable button publish
        function toggleBtnPublish(value) {
            if (typeof value === 'undefined') {
                const invalidCount = $thingForm.find('.form-component.invalid').length;
                $btnPublish.prop('disabled', invalidCount > 0);
                allowPublish = invalidCount === 0;
            } else {
                $btnPublish.prop('disabled', value);
                allowPublish = !value;
            }
        }

        // Call for the first time
        toggleBtnPublish();

        function toggleSyncingLoader() {
            const syncing = Object.keys(timeouts).length > 0;
            if ($syncingLoader.length === 1) {
                if (syncing) {
                    $syncingLoader.removeClass('hidden');
                } else {
                    $syncingLoader.addClass('hidden');
                }
            }
        }

        // Function to save data
        function saveData(object, attr, value) {
            const $formComponent = $('.form-component#'.concat(attr));
            const $errorsContainer = $formComponent.find('.thing-errors');
            if (object === 'value') {
                if ($errorsContainer.length === 0) {return; }
                toggleBtnPublish(true);
            }

            const timeoutID = object.concat(attr);
            clearTimeout(timeouts[timeoutID]);
            timeouts[timeoutID] = setTimeout(() => {
                const formData = {object, data: {}};
                formData.data[attr] = value;
                $.ajax({
                    url: window.location.href,
                    method: 'PUT',
                    data: JSON.stringify(formData),
                    contentType: 'application/json; charset=utf-8',
                    statusCode: {
                        401: (data) => {
                            const loginURL = data.responseJSON.loginURL;
                            window.location.href = loginURL;
                        }
                    },
                    error: (response) => {
                        switch(response.status) {
                            case 400:
                                if (object === 'value') {
                                    $formComponent.addClass('invalid');
                                    const violations = response.responseJSON;
                                    violations.forEach((violation) => {
                                        $errorsContainer.append(`<li>${violation.message}</li>`);
                                    });
                                }
                                break;
                        }
                    },
                    beforeSend: () => {
                        toggleSyncingLoader();
                        if (object === 'value') {
                            $formComponent.removeClass('invalid');
                            $errorsContainer.empty();
                        }
                    }
                }).always(() => {
                    delete timeouts[timeoutID];
                    toggleSyncingLoader();
                    toggleBtnPublish();
                });
            }, 300);
        }

        ['thing', 'value'].forEach((supportedObject) => {
            $('[data-object="'+supportedObject+'"][data-event="input"]').on('input', (e) => {
                const $input = $(e.currentTarget);
                const attr = $input.data('attr');
                const object = $input.data('object');
                saveData(object, attr, $input.val());
            });
        });

        // Listen for publishing
        $btnPublish.on('click', () => {
            if (allowPublish) {
                $.ajax({
                    url: baseUrl.concat('/toggle-publish'),
                    method: 'POST',
                    success: (data) => {
                        window.location.href = data.redirectTo;
                    }
                });
            } else {
                toggleBtnPublish(true);
            }
        });

    }
});