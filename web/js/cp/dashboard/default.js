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

function _possibleConstructorReturn(self, call) {
    if (!self) {
        throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    }
    return call && (typeof call === "object" || typeof call === "function") ? call : self;
}

function _inherits(subClass, superClass) {
    if (typeof superClass !== "function" && superClass !== null) {
        throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
    }
    subClass.prototype = Object.create(superClass && superClass.prototype, {
        constructor: {
            value: subClass,
            enumerable: false,
            writable: true,
            configurable: true
        }
    });
    if (superClass) {
        Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
    }
}

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
                {className: "col-md-3"},
                React.createElement(
                    "div",
                    {className: "ibox-title"},
                    React.createElement(
                        "h5",
                        null,
                        this.props.title
                    )
                ),
                React.createElement(
                    "div",
                    {className: "ibox-content"},
                    React.createElement(
                        "h2",
                        {className: "no-margins"},
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

    var wheelCount = 'Loading..';
    var styleCount = 'Loading..';
    var boltPatternCount = 'Loading..';
    var collectionCount = 'Loading..';

    var userCount = 'Loading..';
    var roleCount = 'Loading..';
    var permissionCount = 'Loading..';

    var inviteCount = 'Loading..';
    var appCount = 'Loading..';

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

    fetch('/cp/dashboard/count?model=role', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        roleCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=permission', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        permissionCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=wheel', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        wheelCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=style', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        styleCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=boltPattern', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        boltPatternCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=collection', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        collectionCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=invite', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        inviteCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=app', {
        method: 'GET',
        credentials: 'include'
    }).then(function (r) {
        return r.json();
    }).then(function (res) {
        appCount = res.count;
        render();
    });

    function render() {
        ReactDOM.render(React.createElement(
            "div",
            {className: "col-md-12"},
            React.createElement(IBoxBlock, {title: "Brand", count: brandCount}),
            React.createElement(IBoxBlock, {title: "Dealer", count: dealerCount}),
            React.createElement(IBoxBlock, {title: "Heading", count: headingCount}),
            React.createElement(IBoxBlock, {title: "Invite", count: inviteCount}),
            React.createElement(IBoxBlock, {title: "Wheel", count: wheelCount}),
            React.createElement(IBoxBlock, {title: "Style [wheels]", count: styleCount}),
            React.createElement(IBoxBlock, {title: "Bolt Pattern [wheels]", count: boltPatternCount}),
            React.createElement(IBoxBlock, {title: "Collection [wheels]", count: collectionCount}),
            React.createElement(IBoxBlock, {title: "User", count: userCount}),
            React.createElement(IBoxBlock, {title: "Role", count: roleCount}),
            React.createElement(IBoxBlock, {title: "Permission", count: permissionCount}),
            React.createElement(IBoxBlock, {title: "App", count: appCount})
        ), document.getElementById('content'));
    }
});
//# sourceMappingURL=default.js.map