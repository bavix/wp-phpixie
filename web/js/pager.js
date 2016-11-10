if (typeof wbsPager === "undefined") {
    function wbsPager(element) {
        var page = $(element).data('page');
        Url.updateSearchParam("page", page);
        location.reload();
    }
}