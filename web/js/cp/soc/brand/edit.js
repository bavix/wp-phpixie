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
                    React.createElement(
                        'a',
                        { href: model.url, title: socialName, target: '__blank' },
                        model.url
                    )
                ),
                React.createElement(
                    'td',
                    null,
                    ' '
                )
            );
        }
    }, {
        key: 'render',
        value: function render() {

            var rows = this.props.rows.map(this.row);

            return React.createElement(
                'table',
                { className: 'table table-striped' },
                this.columns(),
                React.createElement(
                    'tbody',
                    null,
                    rows
                )
            );
        }
    }]);

    return SocialRows;
}(React.Component);

var HeadingRows = function (_React$Component2) {
    _inherits(HeadingRows, _React$Component2);

    function HeadingRows(props) {
        _classCallCheck(this, HeadingRows);

        return _possibleConstructorReturn(this, (HeadingRows.__proto__ || Object.getPrototypeOf(HeadingRows)).call(this, props));
    }

    _createClass(HeadingRows, [{
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
                        'Parent ID'
                    ),
                    React.createElement(
                        'th',
                        null,
                        'Title'
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
                    model.parentId
                ),
                React.createElement(
                    'td',
                    null,
                    model.title
                ),
                React.createElement(
                    'td',
                    null,
                    ' '
                )
            );
        }
    }, {
        key: 'render',
        value: function render() {

            var rows = this.props.rows.map(this.row);

            return React.createElement(
                'table',
                { className: 'table table-striped' },
                this.columns(),
                React.createElement(
                    'tbody',
                    null,
                    rows
                )
            );
        }
    }]);

    return HeadingRows;
}(React.Component);

$(function () {

    // heading

    $('#headingType').select2({
        theme: "bootstrap",
        ajax: {
            url: '/api/soc/heading?limit=15',
            dataType: 'json',
            delay: 350,
            data: function data(params) {
                return {
                    queries: {
                        title: params.term
                    },
                    page: params.page || 1
                };
            },
            processResults: function processResults(data) {
                if (typeof data.message !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data, function (obj) {
                        return { id: obj.id, text: obj.title };
                    })
                };
            },
            cache: true
        }
    });

    /// social
    var socialRows = document.getElementById('socialRows');
    var $formSocial = $('[data-created="social"]');

    // heading
    var headingRows = document.getElementById('headingRows');
    var $formHeading = $('[data-created="heading"]');

    var socialJson = [];
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
            socialJson = json;
        } else {
            socialJson.push(json);
        }

        ReactDOM.render(React.createElement(SocialRows, { rows: socialJson }), socialRows);
    }

    function tableHeadingInit(json) {
        if (typeof json.id === "undefined") {
            headingJson = json;

            ReactDOM.render(React.createElement(HeadingRows, { rows: headingJson }), headingRows);
        } else {
            fetch('/api/soc/heading/' + json.headingId, {
                method: 'GET',
                credentials: 'include'
            }).then(response).then(function (data) {
                headingJson.push({
                    id: json.headingId,
                    parentId: data.parentId,
                    title: data.title,
                    createdAt: data.createdAt,
                    updatedAt: data.updatedAt
                });

                ReactDOM.render(React.createElement(HeadingRows, { rows: headingJson }), headingRows);
            });
        }
    }

    $formHeading.submit(function (event) {

        event.preventDefault();

        var form = new FormData(this);

        fetch($formHeading.attr('action'), {
            method: $formHeading.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableHeadingInit).catch(function () {
            return undefined;
        });
    });

    $formSocial.submit(function (event) {

        event.preventDefault();

        var form = new FormData(this);

        fetch($formSocial.attr('action'), {
            method: $formSocial.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableInit).catch(function () {
            return undefined;
        });
    });

    fetch($formSocial.attr('action'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(function () {
        return undefined;
    });

    fetch('/api/soc/heading?terms[brands.id]=' + $formHeading.data('id'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableHeadingInit).catch(function () {
        return undefined;
    });
});
//# sourceMappingURL=edit.js.map