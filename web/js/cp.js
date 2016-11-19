$(function () {

    $("select").select2();
    // $('.table').DataTable();

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

    interval(entryDate);

});