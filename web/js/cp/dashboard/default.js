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

            var first = React.createElement(
                "span",
                { className: "label label-primary pull-right" },
                "loading.."
            );
            var last = void 0;

            if (typeof this.props.data.count !== "undefined") {
                first = React.createElement(
                    "span",
                    { className: "label label-primary pull-right" },
                    this.props.data.count
                );

                if (this.props.data.active !== this.props.data.count) {
                    last = React.createElement(
                        "span",
                        { className: "label label-danger pull-right" },
                        this.props.data.active
                    );
                }
            }

            return React.createElement(
                "div",
                { className: "col-sm-6 col-xs-4 col-md-4 col-lg-3" },
                React.createElement(
                    "div",
                    { className: "ibox-title" },
                    first,
                    " ",
                    last,
                    React.createElement(
                        "h5",
                        null,
                        this.props.data.title
                    )
                ),
                React.createElement(
                    "div",
                    { className: "ibox-content" },
                    React.createElement(
                        "canvas",
                        { id: "chart-" + this.props.data.id, width: "100%" },
                        " Loading ..."
                    )
                )
            );
        }
    }]);

    return IBoxBlock;
}(React.Component);

function initChart(data) {
    new Chart(document.getElementById("chart-" + data.id).getContext('2d'), {
        type: 'pie',
        data: {
            labels: ["Active", "No Active"],
            datasets: [{
                backgroundColor: data.backgroundColor,
                data: data.data
            }]
        }
    });
}

$(function () {

    var blocks = {
        brand: {
            id: 'brand',
            title: 'Brand',
            backgroundColor: ['#e74c3c']
        },

        dealer: {
            id: 'dealer',
            title: 'Dealer',
            backgroundColor: ['#4BC0C0']
        },

        heading: {
            id: 'heading',
            title: 'Heading',
            backgroundColor: ['#36A2EB']
        },

        invite: {
            id: 'invite',
            title: 'Invite',
            backgroundColor: ['#c2c4d1']
        },

        wheel: {
            id: 'wheel',
            title: 'Wheel',
            backgroundColor: ['#ffe6ab']
        },

        style: {
            id: 'style',
            title: 'Style [Wheels]',
            backgroundColor: ['#36A2EB']
        },

        boltPattern: {
            id: 'boltPattern',
            title: 'Bolt Pattern [Wheels]',
            backgroundColor: ['#c2c4d1']
        },

        collection: {
            id: 'collection',
            title: 'Collection [Wheels]',
            backgroundColor: ['#4BC0C0']
        },

        user: {
            id: 'user',
            title: 'User',
            backgroundColor: ['#ffe6ab']
        },

        app: {
            id: 'app',
            title: 'Application',
            backgroundColor: ['#FF6384']
        }
    };

    var _loop = function _loop(model) {
        fetch('/cp/dashboard/count?model=' + model, {
            method: 'GET',
            credentials: 'include'
        }).then(function (r) {
            return r.json();
        }).then(function (res) {
            blocks[blocks[model].id].count = res.count;
            blocks[blocks[model].id].active = res.active;
            blocks[blocks[model].id].data = [res.active, res.count - res.active];
            render(blocks[blocks[model].id]);
        });
    };

    for (var model in blocks) {
        _loop(model);
    }

    function render(data) {
        var content = document.getElementById('content');

        ReactDOM.render(React.createElement(
            "div",
            { className: "col-lg-12" },
            React.createElement(IBoxBlock, { data: blocks.brand }),
            React.createElement(IBoxBlock, { data: blocks.dealer }),
            React.createElement(IBoxBlock, { data: blocks.heading }),
            React.createElement(IBoxBlock, { data: blocks.invite }),
            React.createElement(IBoxBlock, { data: blocks.wheel }),
            React.createElement(IBoxBlock, { data: blocks.style }),
            React.createElement(IBoxBlock, { data: blocks.boltPattern }),
            React.createElement(IBoxBlock, { data: blocks.collection }),
            React.createElement(IBoxBlock, { data: blocks.user }),
            React.createElement(IBoxBlock, { data: blocks.app })
        ), content);

        initChart(data);
    }
});
//# sourceMappingURL=default.js.map