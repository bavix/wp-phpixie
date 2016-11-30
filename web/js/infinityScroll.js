/**
 * @param {*} options
 * @returns {InfinityScroll}
 * @constructor
 */
const InfinityScroll = function (options) {

    this.window = typeof options['window'] !== 'undefined' ? options['window'] : window;
    this.document = typeof options['document'] !== 'undefined' ? options['document'] : document;

    this.top = function () {
        return (typeof this.window.pageYOffset !== 'undefined') ?
            this.window.pageYOffset :
            (this.document.documentElement || this.document.body.parentNode || this.document.body).scrollTop;
    };

    this.maxTop = function () {
        return this.documentHeight() - this.innerHeight();
    };

    this.documentHeight = function () {
        const body = this.document.body;
        const html = this.document.documentElement;

        return Math.max(
            body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight
        );
    };

    this.innerHeight = function () {
        return this.window.innerHeight;
    };

    this.scrollPercent = function () {
        return this.top() / this.maxTop();
    };

    return this;

};

/**
 * @param callback callback
 * @param {*} options
 */
jQuery.fn.infinityScroll = function (callback, options) {

    const infinityScroll = new InfinityScroll(options);
    const percent = typeof options['percent'] !== "undefined" ? options['percent'] : .70;
    const current = this;

    this.scroll(function () {
        if (infinityScroll.scrollPercent() > percent) {
            callback(infinityScroll);
        }
    })

};

// $(window).infinityScroll(function (infinityScroll) {
//     console.log(infinityScroll.scrollPercent());
// });