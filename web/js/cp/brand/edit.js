'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var SocialRows = function (_React$Component) {
    _inherits(SocialRows, _React$Component);

    function SocialRows(props) {
        _classCallCheck(this, SocialRows);

        return _possibleConstructorReturn(this, (SocialRows.__proto__ || Object.getPrototypeOf(SocialRows)).call(this, props));
    }

    _createClass(SocialRows, [{
        key: 'columns',
        value: function columns() {
            return React.createElement(
                'thead',
                null,
                React.createElement(
                    'tr',
                    null,
                    React.createElement(
                        'th',
                        null,
                        'ID'
                    ),
                    React.createElement(
                        'th',
                        null,
                        'Type'
                    ),
                    React.createElement(
                        'th',
                        null,
                        'URL'
                    ),
                    React.createElement(
                        'th',
                        null,
                        'Actions'
                    )
                )
            );
        }
    }, {
        key: 'row',
        value: function row(model) {
            var socialName = $('#socialType [value="' + model.socialId + '"]').text();
            return React.createElement(
                'tr',
                { key: model.id },
                React.createElement(
                    'td',
                    null,
                    model.id
                ),
                React.createElement(
                    'td',
                    null,
                    socialName
                ),
                React.createElement(
                    'td',
                    null,
                    model.url
                ),
                React.createElement('td', null)
            );
        }
    }, {
        key: 'render',
        value: function render() {

            var rows = this.props.rows.map(this.row);

            var table = React.createElement(
                'table',
                { className: 'table table-striped' },
                this.columns(),
                React.createElement(
                    'tbody',
                    null,
                    rows
                )
            );

            return table;
        }
    }]);

    return SocialRows;
}(React.Component);

$(function () {

    var socialRows = document.getElementById('socialRows');
    var $formSocial = $('[data-created="social"]');
    var socialJson = [];

    $formSocial.submit(function (event) {

        event.preventDefault();

        var form = new FormData(this);

        fetch($formSocial.attr('action'), {
            method: $formSocial.attr('method'),
            credentials: 'include',
            body: form
        }).then(function (response) {

            if (response.status === 201 || response.status === 200) {
                return response.json();
            }

            var error = new Error(response.statusText);
            error.response = response;
            throw error;
        }).then(function (json) {

            socialJson.push(json);

            ReactDOM.render(React.createElement(SocialRows, { rows: socialJson }), socialRows);
        }).catch(function (error) {
            // todo
        });
    });

    fetch($formSocial.attr('action'), {
        method: 'GET',
        credentials: 'include'
    }).then(function (response) {

        if (response.status === 201 || response.status === 200) {
            return response.json();
        }

        var error = new Error(response.statusText);
        error.response = response;
        throw error;
    }).then(function (json) {

        socialJson = json;

        ReactDOM.render(React.createElement(SocialRows, { rows: socialJson }), socialRows);
    }).catch(function (error) {
        // todo
    });
});
//# sourceMappingURL=edit.js.map