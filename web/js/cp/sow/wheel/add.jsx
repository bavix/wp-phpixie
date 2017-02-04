$(function () {

    $('[data-created]').submit(function (event) {

        event.preventDefault();

        let $self = $(this);
        let form = new FormData(this);

        fetch($self.attr('action'), {
            method: $self.attr('method'),
            credentials: 'include',
            body: form
        }).then(function (response) {
            if (response.status === 201 || response.status === 200) {
                return response.json();
            }

            let error = new Error(response.statusText);
            error.response = response;
            throw error;
        }).then(function (json) {

            location.href = '/cp/sow/wheel/edit/' + json.id;

        }).catch(function (error) {
            let $message = $self.find('.alert');

            if (!$message.length) {
                $self.find('div:first-child').prepend('<div class="alert"></div>');
                $message = $self.find('.alert');
            }

            error.response.json().then((json) => $message.addClass('alert-danger').text(json.message));
        });

    });

    $('#brandsSelect').select2({
        theme: "bootstrap",
        ajax: {
            url: '/api/soc/brand?limit=15',
            dataType: 'json',
            delay: 350,
            data: function (params) {
                return {
                    queries: {
                        name: params.term
                    },
                    page: params.page || 1
                }
            },
            processResults: function (data) {
                if (typeof data.message !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data, function (obj) {
                        return {id: obj.id, text: obj.name};
                    })
                };
            },
            cache: true
        }
    }).on('change', function (e) {

        let brandId = $(e.target).val();

        $('#collectionSelect').select2({
            theme: "bootstrap",
            ajax: {
                url: '/api/sow/collection?queries[brandId]=' + brandId + '&limit=15',
                dataType: 'json',
                delay: 350,
                data: function (params) {
                    return {
                        queries: {
                            name: params.term
                        },
                        page: params.page || 1
                    }
                },
                processResults: function (data) {
                    if (typeof data.message !== "undefined") {
                        return {
                            results: {}
                        };
                    }

                    return {
                        results: $.map(data, function (obj) {
                            return {id: obj.id, text: obj.name};
                        })
                    };
                },
                cache: true
            }
        });

    });

});