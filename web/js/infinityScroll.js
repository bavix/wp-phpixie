"use strict";

var _createClass = function () {
    function defineProperties(target, props) {
        for (var i = 0; i < props.length; i++) {
            var descriptor = props[i];
            descriptor.enumerable = descriptor.enumerable || false;
            descriptor.configurable = true;
            if ("value" in descriptor) {
                descriptor.writable = true;
            }
            Object.defineProperty(target, descriptor.key, descriptor);
        }
    }

    return function (Constructor, protoProps, staticProps) {
        if (protoProps) {
            defineProperties(Constructor.prototype, protoProps);
        }
        if (staticProps) {
            defineProperties(Constructor, staticProps);
        }
        return Constructor;
    };
}();

function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}

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
    }
    catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
    }
    finally {
        try {
            if (!_iteratorNormalCompletion && _iterator.return) {
                _iterator.return();
            }
        }
        finally {
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

var InfinityScroll = function () {
    function InfinityScroll(options) {
        _classCallCheck(this, InfinityScroll);

        options = defaultValue(options, {
            window: window,
            document: window.document
        });

        this.window = options.window;
        this.document = options.document;
    }

    _createClass(InfinityScroll, [{
        key: "hasPageYOffset",
        value: function hasPageYOffset() {
            return typeof this.window.pageYOffset !== "undefined";
        }
    }, {
        key: "pageYOffset",
        value: function pageYOffset() {
            return this.window.pageYOffset;
        }
    }, {
        key: "scrollTop",
        value: function scrollTop() {
            return (this.document.documentElement || this.document.body.parentNode || this.document.body).scrollTop;
        }
    }, {
        key: "top",
        value: function top() {
            return this.hasPageYOffset() ? this.pageYOffset() : this.scrollTop();
        }
    }, {
        key: "documentHeight",
        value: function documentHeight() {
            var body = this.document.body;
            var html = this.document.documentElement;

            return Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
        }
    }, {
        key: "innerHeight",
        value: function innerHeight() {
            return this.window.innerHeight;
        }
    }, {
        key: "scrollPercent",
        value: function scrollPercent() {
            return this.top() / this.maxTop();
        }
    }, {
        key: "maxTop",
        value: function maxTop() {
            return this.documentHeight() - this.innerHeight();
        }
    }]);

    return InfinityScroll;
}();

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
        if (scrollObject.scrollPercent() > percent) {
            callback(scrollObject);
        }
    });

    return this;
};
//# sourceMappingURL=infinityScroll.js.map