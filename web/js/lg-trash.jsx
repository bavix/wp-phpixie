(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module unless amdModuleId is set
        define([], function () {
            return (factory());
        });
    }
    else if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = factory();
    }
    else {
        factory();
    }
}(this, function () {

    (function ($, window, document, undefined) {

        'use strict';

        let defaults = {
            trash: true
        };

        let Trash = function (element) {

            // get lightGallery core plugin data
            this.core = $(element).data('lightGallery');

            this.$el = $(element);

            // extend module defalut settings with lightGallery core settings
            this.core.s = $.extend({}, defaults, this.core.s);

            this.init();

            return this;
        };

        Trash.prototype.init = function () {
            if (this.core.s.trash) {
                let button = '<span class="lg-trash lg-icon"><i class="fa fa-trash"></i></span>';
                this.core.$outer.find('.lg-toolbar').append(button);
                this.trash();
            }
        };

        Trash.prototype.trash = function () {
            let _this = this;
            this.core.$outer.find('.lg-trash').on('click.lg', function () {

                let $current = _this.core.$items.eq(_this.core.index);

                fetch('/api' + $current.attr('data-trash'), {
                    method: 'DELETE',
                    credentials: 'include'
                }).then(function (response) {

                    if (response.status == 200) {
                        return response.json();
                    }

                    console.log(response.status);

                    if (response.status != 204) {
                        let error = new Error(response.statusText);
                        error.response = response;
                        throw error;
                    }

                    return [];

                }).then(function (json) {
                    $current.remove();
                    swal(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                }).catch(function (res) {
                    swal(
                        'Deleted!',
                        'Your file has not been deleted.',
                        'error'
                    );
                });

            });
        };

        Trash.prototype.destroy = function () {
            this.core.$outer.find('.lg-trash').remove();
        };

        $.fn.lightGallery.modules.trash = Trash;

    })(jQuery, window, document);

}));