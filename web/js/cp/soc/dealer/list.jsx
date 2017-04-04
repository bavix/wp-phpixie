// ReactDOM.render(
//     <h1>Hello, world!</h1>,
//     document.getElementById('root')
// );

$(function () {

    $('#activeSelect').select2({
        theme: "bootstrap"
    });

    $('#dealersSelect').select2({
        theme: "bootstrap",
        ajax: {
            url: '/api/soc/dealer?limit=15',
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
                if (typeof data.error_description !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data.data, function (obj) {
                        return {id: obj.id, text: obj.name};
                    })
                };
            },
            cache: true
        }
    });

});