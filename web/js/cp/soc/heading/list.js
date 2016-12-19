"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var HeadingRows = function (_React$Component) {
    _inherits(HeadingRows, _React$Component);

    function HeadingRows(props) {
        _classCallCheck(this, HeadingRows);

        return _possibleConstructorReturn(this, (HeadingRows.__proto__ || Object.getPrototypeOf(HeadingRows)).call(this, props));
    }

    _createClass(HeadingRows, [{
        key: "columns",
        value: function columns() {
            return React.createElement(
                "thead",
                null,
                React.createElement(
                    "tr",
                    null,
                    React.createElement(
                        "th",
                        null,
                        "ID"
                    ),
                    React.createElement(
                        "th",
                        null,
                        "Parent Id"
                    ),
                    React.createElement(
                        "th",
                        null,
                        "Title"
                    ),
                    React.createElement(
                        "th",
                        null,
                        "Updated At"
                    ),
                    React.createElement(
                        "th",
                        null,
                        "Actions"
                    )
                )
            );
        }
    }, {
        key: "row",
        value: function row(model) {
            return React.createElement(
                "tr",
                { key: model.id },
                React.createElement(
                    "td",
                    null,
                    model.id
                ),
                React.createElement(
                    "td",
                    null,
                    model.parentId
                ),
                React.createElement(
                    "td",
                    null,
                    model.title
                ),
                React.createElement("td", { className: "entry-date", "data-time": model.updatedAt }),
                React.createElement(
                    "td",
                    null,
                    " "
                )
            );
        }
    }, {
        key: "render",
        value: function render() {

            var rows = this.props.rows.map(this.row);

            return React.createElement(
                "table",
                { className: "table table-striped" },
                this.columns(),
                React.createElement(
                    "tbody",
                    null,
                    rows
                )
            );
        }
    }]);

    return HeadingRows;
}(React.Component);

$(function () {

    var headingRows = document.getElementById('content');
    var headingJson = [];

    function response(response) {
        if (response.status === 201 || response.status === 200) {
            return response.json();
        }

        var error = new Error(response.statusText);
        error.response = response;
        throw error;
    }

    function tableInit(json) {
        if (typeof json.id === "undefined") {
            headingJson = json;
        } else {
            headingJson.push(json);
        }

        ReactDOM.render(React.createElement(HeadingRows, { rows: headingJson }), headingRows);
    }

    fetch('/api/soc/heading?sort[id]=desc', {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(function () {
        return undefined;
    });
});
//# sourceMappingURL=list.js.map