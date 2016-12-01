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

class InfinityScroll {

    constructor(options) {

        options = defaultValue(options, {
            window: window,
            document: window.document
        });

        this.window = options.window;
        this.document = options.document;

    }

    hasPageYOffset () {
        return typeof this.window.pageYOffset !== "undefined";
    }

    pageYOffset () {
        return this.window.pageYOffset;
    }

    scrollTop () {
        return (this.document.documentElement || this.document.body.parentNode || this.document.body).scrollTop;
    }

    top () {
        return ( this.hasPageYOffset() ? this.pageYOffset() : this.scrollTop() );
    }

    documentHeight () {
        const body = this.document.body;
        const html = this.document.documentElement;

        return Math.max(
            body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight
        );
    };

    innerHeight () {
        return this.window.innerHeight;
    }

    scrollPercent () {
        return this.top() / this.maxTop();
    }

    maxTop () {
        return this.documentHeight() - this.innerHeight();
    }

}

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