"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var IBoxBlock = function (_React$Component) {
    _inherits(IBoxBlock, _React$Component);

    function IBoxBlock(props) {
        _classCallCheck(this, IBoxBlock);

        return _possibleConstructorReturn(this, (IBoxBlock.__proto__ || Object.getPrototypeOf(IBoxBlock)).call(this, props));
    }

    _createClass(IBoxBlock, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "col-md-3" },
                React.createElement(
                    "div",
                    { className: "ibox-title" },
                    React.createElement(
                        "h5",
                        null,
                        this.props.title
                    )
                ),
                React.createElement(
                    "div",
                    { className: "ibox-content" },
                    React.createElement(
                        "h2",
                        { className: "no-margins" },
                        " ",
                        this.props.count,
                        " "
                    )
                )
            );
        }
    }]);

    return IBoxBlock;
}(React.Component);

$(function () {

    var brandCount = 'Loading..';
    var dealerCount = 'Loading..';
    var headingCount = 'Loading..';
    var userCount = 'Loading..';

    fetch('/cp/dashboard/count?model=brand', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        brandCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=dealer', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        dealerCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=heading', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        headingCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=user', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        userCount = res.count;
        render();
    });

    function render() {
        ReactDOM.render(React.createElement(
            "div",
            { className: "col-md-12" },
            React.createElement(IBoxBlock, { title: "Brand", count: brandCount }),
            React.createElement(IBoxBlock, { title: "Dealer", count: dealerCount }),
            React.createElement(IBoxBlock, { title: "Heading", count: headingCount }),
            React.createElement(IBoxBlock, { title: "User", count: userCount })
        ), document.getElementById('content'));
    }
});
//# sourceMappingURL=default.js.map