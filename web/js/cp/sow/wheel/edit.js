'use strict';

$(function () {

    $('[data-updated="wheel"]').submit(function (event) {

        event.preventDefault();

        var $self = $(this);
        var form = new FormData(this);

        fetch($self.attr('action'), {
            method: $self.attr('method'),
            credentials: 'include',
            body: form
        }).then(function (response) {
            if (response.status === 201 || response.status === 200) {
                return response.json();
            }

            var error = new Error(response.statusText);
            error.response = response;
            throw error;
        }).then(function (json) {

            // fixme
            alert('Информация обновлена!');
        }).catch(function (error) {
            var $message = $self.find('.alert');

            if (!$message.length) {
                $self.find('div:first-child').prepend('<div class="alert"></div>');
                $message = $self.find('.alert');
            }

            error.response.json().then(function (json) {
                return $message.addClass('alert-danger').text(json.error_description);
            });
        });
    });

    // fixme
    $('#styleWheel').select2({
        theme: "bootstrap"
    });
});
//# sourceMappingURL=edit.js.map