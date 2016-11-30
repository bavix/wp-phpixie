"use strict";

// https://jsfiddle.net/69z2wepo/14377/

Object.prototype.defaultValue = function (defObject) {
    var _iteratorNormalCompletion = true;
    var _didIteratorError = false;
    var _iteratorError = undefined;

    try {
        for (var _iterator = Object.keys(defObject)[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            var key = _step.value;

            if (!this.hasOwnProperty(key)) {
                this[key] = defObject[item];
            }
        }
    } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
    } finally {
        try {
            if (!_iteratorNormalCompletion && _iterator.return) {
                _iterator.return();
            }
        } finally {
            if (_didIteratorError) {
                throw _iteratorError;
            }
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
var InfinityScroll = function InfinityScroll(options) {
    var _this = this;

    options = defaultValue(options, {
        window: window,
        document: window.document
    });

    this.window = options.window;
    this.document = options.document;

    this.hasPageYOffset = function () {
        return typeof _this.window.pageYOffset !== "undefined";
    };
    this.pageYOffset = function () {
        return _this.window.pageYOffset;
    };
    this.scrollTop = function () {
        return (_this.document.documentElement || _this.document.body.parentNode || _this.document.body).scrollTop;
    };

    this.top = function () {
        return _this.hasPageYOffset() ? _this.pageYOffset() : _this.scrollTop();
    };

    this.documentHeight = function () {
        var body = this.document.body;
        var html = this.document.documentElement;

        return Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
    };

    this.innerHeight = function () {
        return _this.window.innerHeight;
    };
    this.scrollPercent = function () {
        return _this.top() / _this.maxTop();
    };
    this.maxTop = function () {
        return _this.documentHeight() - _this.innerHeight();
    };

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

    var scrollObject = new InfinityScroll(options);

    options = defaultValue(options, {
        percent: 0.99
    });

    var percent = options.percent;

    callback.bind(this);

    if (!this.hasScrollBar()) {
        callback(scrollObject);
    }

    this.scroll(function () {
        if (scrollObject.scrollPercent() > percent) callback(scrollObject);
    });

    return this;
};
//# sourceMappingURL=infinityScroll.js.map