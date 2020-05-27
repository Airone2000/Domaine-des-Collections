import $ from 'jquery';
$(() => {


    const $thingForm = $('#thing-form');

    if ($thingForm.length === 1) {
        const timeouts = {};

        // Function to save data
        function saveData(object, attr, value) {
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
                    success: () => {
                        console.log('ok');
                    },
                    error: () => {
                        console.log('error');
                    }
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

    }
});