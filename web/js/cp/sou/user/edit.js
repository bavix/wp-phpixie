'use strict';

function response(response) {
    if (response.status === 201 || response.status === 200) {
        return response.json();
    }

    if (response.status !== 204) {
        var error = new Error(response.statusText);
        error.response = response;
        throw error;
    }

    return [];
}

$(function () {

    $('[data-updated="user"]').submit(function () {

        event.preventDefault();

        var form = new FormData(this);

        fetch($(this).attr('action'), {
            method: $(this).attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(function (response) {

            alert('Информация обновлена!');
            console.log(response);
        }).catch(function () {
            return undefined;
        });
    });
});
//# sourceMappingURL=edit.js.map