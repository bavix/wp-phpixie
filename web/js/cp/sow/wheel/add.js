'use strict';

$(function () {

    $('[data-created]').submit(function (event) {

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

            location.href = '/cp/sow/wheel/edit/' + json.id;
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

    $('#brandsSelect').select2({
        theme: "bootstrap",
        ajax: {
            url: '/api/soc/brand?limit=15',
            dataType: 'json',
            delay: 350,
            data: function data(params) {
                return {
                    queries: {
                        name: params.term
                    },
                    page: params.page || 1
                };
            },
            processResults: function processResults(data) {
                if (typeof data.error_description !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data.data, function (obj) {
                        return { id: obj.id, text: obj.name };
                    })
                };
            },
            cache: true
        }
    }).on('change', function (e) {

        var brandId = $(e.target).val();

        $('#collectionSelect').select2({
            theme: "bootstrap",
            ajax: {
                url: '/api/sow/collection?terms[brandId]=' + brandId + '&limit=15',
                dataType: 'json',
                delay: 350,
                data: function data(params) {
                    return {
                        queries: {
                            name: params.term
                        },
                        page: params.page || 1
                    };
                },
                processResults: function processResults(data) {
                    if (typeof data.error_description !== "undefined") {
                        return {
                            results: {}
                        };
                    }

                    return {
                        results: $.map(data.data, function (obj) {
                            return { id: obj.id, text: obj.name };
                        })
                    };
                },
                cache: true
            }
        });
    });
});
//# sourceMappingURL=add.js.map