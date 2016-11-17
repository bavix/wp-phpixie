$(function () {

    var config = {
        '.chosen-select': {},
        '.chosen-select-deselect': {allow_single_deselect: true},
        '.chosen-select-no-single': {disable_search_threshold: 10},
        '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
        '.chosen-select-rtl': {rtl: true},
        '.chosen-select-width': {width: "95%"}
    };

    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

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

    entryDate();

    requestInterval(entryDate, 6000);

});