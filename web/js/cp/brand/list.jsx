// ReactDOM.render(
//     <h1>Hello, world!</h1>,
//     document.getElementById('root')
// );

$(function () {
    $('#brandsSelect').select2({
        theme: "bootstrap",
        ajax: {
            url: '/api/soc/brand',
            dataType: 'json',
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