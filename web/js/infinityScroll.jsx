// https://jsfiddle.net/69z2wepo/14377/

Object.prototype.defaultValue = function (defObject) {
    for (let key of Object.keys(defObject)) {
        if (!this.hasOwnProperty(key)) {
            this[key] = defObject[item];
        }
    }

    return this;
};

function defaultValue(object, defObject) {
    if (object === undefined) {
        return defObject;
    }

    return object.defaultValue(defObject);
}

/**
 * @param {*} options
 * @returns {InfinityScroll}
 * @constructor
 */
const InfinityScroll = function (options) {

    options = defaultValue(options, {
        window: window,
        document: window.document
    });

    this.window = options.window;
    this.document = options.document;

    this.hasPageYOffset = () => typeof this.window.pageYOffset !== "undefined";
    this.pageYOffset = () => this.window.pageYOffset;
    this.scrollTop = () => (this.document.documentElement || this.document.body.parentNode || this.document.body).scrollTop;

    this.top = () => ( this.hasPageYOffset() ? this.pageYOffset() : this.scrollTop() );

    this.documentHeight = function () {
        const body = this.document.body;
        const html = this.document.documentElement;

        return Math.max(
            body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight
        );
    };

    this.innerHeight = () => this.window.innerHeight;
    this.scrollPercent = () => this.top() / this.maxTop();
    this.maxTop = () => this.documentHeight() - this.innerHeight();

    return this;

};

jQuery.fn.hasScrollBar = function () {
    return this.get(0).scrollHeight > this.height();
};

/**
 * @param callback callback
 * @param {*} options
 */
jQuery.fn.infinityScroll = function (callback, options) {

    const scrollObject = new InfinityScroll(options);

    options = defaultValue(options, {
        percent: 0.99
    });

    const percent = options.percent;

    callback.bind(this);

    if (!this.hasScrollBar()) {
        callback(scrollObject);
    }

    this.scroll(() => {
        if (scrollObject.scrollPercent() > percent)
            callback(scrollObject);
    });

    return this;

};