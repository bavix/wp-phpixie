'use strict';

// ReactDOM.render(
//     <h1>Hello, world!</h1>,
//     document.getElementById('root')
// );

$(function () {
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
                if (typeof data.message !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data, function (obj) {
                        return { id: obj.id, text: obj.name };
                    })
                };
            },
            cache: true
        }
    });
});
//# sourceMappingURL=list.js.map