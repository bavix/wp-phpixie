var filerObject = {
    theme: "dragdropbox",
    dragDrop: {},
    extensionsImage: ['jpg', 'jpeg', 'png'],
    changeInput: '<div class="jFiler-input-dragDrop">' +
    '<div class="jFiler-input-inner">' +
    '<div class="jFiler-input-icon">' +
    '<i class="icon-jfi-cloud-up-o"></i>' +
    '</div>' +
    '<div class="jFiler-input-text">' +
    '<h3>Drag&Drop files here</h3> ' +
    '<span style="display:inline-block; margin: 15px 0">or</span>' +
    '</div>' +
    '<a class="jFiler-input-choose-btn btn-custom blue-light">Browse Files</a>' +
    '</div>' +
    '</div>'
};

$(function () {

    $("select").select2({
        theme: "bootstrap"
    });

    function entryDate() {
        $(".entry-date").html(function (index, value) {
            var $current = $(this);
            var time = $current.data('time');
            var format = $current.data('timeFormat');

            if (format === undefined) {
                format = "YYYY-MM-DDTHH:mm:ss";
            }

            return moment(time, format).fromNow();
        });
    }

    function interval(callback) {
        callback();
        return requestAnimFrame(function () {
            interval(callback)
        });
    }

    function ajaxDelete(button) {

        var $current = $(button);

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-primary'
        }).then(function () {

            fetch($current.attr('href'), {
                method: 'DELETE',
                credentials: 'include'
            }).then(function (response) {

                if (response.status == 200) {
                    return response.json();
                }

                var error = new Error(response.statusText);
                error.response = response;
                throw error;

            }).then(function (json) {
                $current.parents('tr').remove();
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                );
            }).catch(function () {
                swal(
                    'Deleted!',
                    'Your file has not been deleted.',
                    'error'
                );
            });

        });

        return false;
    }

    $('a.btn.btn-danger.trash-data').click(function () {
        return ajaxDelete(this);
    });

    interval(entryDate);

});