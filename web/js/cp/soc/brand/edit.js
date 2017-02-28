'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

function response(response) {
    if (response.status === 201 || response.status === 200) {
        return response.json();
    }

    if (response.status !== 204) {
        var error = new Error(response.statusText);
        error.response = response;
        throw error;
    }

    return [];
}

var brandId = void 0;

var ButtonDelete = function (_React$Component) {
    _inherits(ButtonDelete, _React$Component);

    function ButtonDelete(props) {
        _classCallCheck(this, ButtonDelete);

        var _this = _possibleConstructorReturn(this, (ButtonDelete.__proto__ || Object.getPrototypeOf(ButtonDelete)).call(this, props));

        _this.methodDelete = _this.methodDelete.bind(_this);
        return _this;
    }

    _createClass(ButtonDelete, [{
        key: 'methodDelete',
        value: function methodDelete(event) {
            var _this2 = this;

            var node = event.target.parentNode.parentNode;

            if (node.tagName != 'TR') {
                node = node.parentNode;
            }

            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-primary'
            }).then(function () {

                fetch(_this2.props.api, {
                    method: 'DELETE',
                    credentials: 'include'
                }).then(response).then(function (json) {

                    $(node).remove();
                    swal('Deleted!', 'Your data has been deleted.', 'success');
                }).catch(function () {
                    swal('Deleted!', 'Your data has not been deleted.', 'error');
                });
            });
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'a',
                { onClick: this.methodDelete, className: 'btn btn-danger' },
                React.createElement(
                    'i',
                    { className: 'fa fa-trash' },
                    ' '
                ),
                ' Delete'
            );
        }
    }]);

    return ButtonDelete;
}(React.Component);

var SocialRows = function (_React$Component2) {
    _inherits(SocialRows, _React$Component2);

    function SocialRows(props) {
        _classCallCheck(this, SocialRows);

        var _this3 = _possibleConstructorReturn(this, (SocialRows.__proto__ || Object.getPrototypeOf(SocialRows)).call(this, props));

        _this3.state = {
            data: props.rows
        };
        return _this3;
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
            var socialName = void 0;
            if (typeof model.social === "undefined") {
                socialName = $('#socialType [value="' + model.socialId + '"]').text();
            } else {
                socialName = model.social.title;
            }
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
                    React.createElement(ButtonDelete, { key: model.id, api: '/api/soc/brand/' + model.brandId + '/social/' + model.socialId })
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

var HeadingRows = function (_React$Component3) {
    _inherits(HeadingRows, _React$Component3);

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
                    React.createElement(ButtonDelete, { key: model.id, api: '/api/soc/brand/' + model.brandId + '/heading/' + model.id })
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

var DealerRows = function (_React$Component4) {
    _inherits(DealerRows, _React$Component4);

    function DealerRows(props) {
        _classCallCheck(this, DealerRows);

        return _possibleConstructorReturn(this, (DealerRows.__proto__ || Object.getPrototypeOf(DealerRows)).call(this, props));
    }

    _createClass(DealerRows, [{
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
                        'Name'
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
                    model.name
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement(ButtonDelete, { key: model.id, api: '/api/soc/brand/' + model.brandId + '/dealer/' + model.id })
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

    return DealerRows;
}(React.Component);

var AddressRows = function (_React$Component5) {
    _inherits(AddressRows, _React$Component5);

    function AddressRows(props) {
        _classCallCheck(this, AddressRows);

        return _possibleConstructorReturn(this, (AddressRows.__proto__ || Object.getPrototypeOf(AddressRows)).call(this, props));
    }

    _createClass(AddressRows, [{
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
                        'Country'
                    ),
                    React.createElement(
                        'th',
                        null,
                        'City'
                    ),
                    React.createElement(
                        'th',
                        null,
                        'Street'
                    ),
                    React.createElement(
                        'th',
                        null,
                        'Number Street'
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
                    model.country
                ),
                React.createElement(
                    'td',
                    null,
                    model.city
                ),
                React.createElement(
                    'td',
                    null,
                    model.street
                ),
                React.createElement(
                    'td',
                    null,
                    model.streetNumber
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement(ButtonDelete, { key: model.id, api: '/api/soc/brand/' + brandId + '/address/' + model.id })
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

    return AddressRows;
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
                if (typeof data.error_description !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data.data, function (obj) {
                        return { id: obj.id, text: obj.title };
                    })
                };
            },
            cache: true
        }
    });

    $('#dealerType').select2({
        theme: "bootstrap",
        ajax: {
            url: '/api/soc/dealer?limit=15',
            dataType: 'json',
            delay: 350,
            data: function data(params) {
                return {
                    queries: {
                        name: params.term
                    },
                    page: params.page || 1
                };
            },
            processResults: function processResults(data) {
                if (typeof data.error_description !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data.data, function (obj) {
                        return { id: obj.id, text: obj.name };
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

    // dealer
    var dealerRows = document.getElementById('dealerRows');
    var $formDealer = $('[data-created="dealer"]');

    // address
    var addressRows = document.getElementById('addressRows');
    var $formAddress = $('[data-created="address"]');

    var socialJson = [];
    var headingJson = [];
    var dealerJson = [];
    var addressJson = [];

    brandId = $formDealer.data('id');

    function tableInit(json) {
        if (typeof json.id === "undefined") {
            socialJson = json.data;
        } else {
            socialJson.push(json);
        }

        ReactDOM.render(React.createElement(SocialRows, { rows: socialJson }), socialRows);
    }

    function tableAddressInit(json) {
        if (typeof json.id === "undefined") {
            addressJson = json.data;
        } else {
            addressJson.push(json);
        }

        ReactDOM.render(React.createElement(AddressRows, { rows: addressJson }), addressRows);
    }

    function tableHeadingInit(json) {
        if (typeof json.data !== "undefined") {

            json = json.data.map(function (data) {
                data.brandId = $formHeading.data('id');

                return data;
            });

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
                    brandId: json.brandId,
                    title: data.title,
                    createdAt: data.createdAt,
                    updatedAt: data.updatedAt
                });

                ReactDOM.render(React.createElement(HeadingRows, { rows: headingJson }), headingRows);
            });
        }
    }

    function tableDealerInit(json) {
        if (typeof json.data !== "undefined") {

            json = json.data.map(function (data) {
                data.brandId = $formDealer.data('id');

                return data;
            });

            dealerJson = json;

            ReactDOM.render(React.createElement(DealerRows, { rows: dealerJson }), dealerRows);
        } else {
            fetch('/api/soc/dealer/' + json.dealerId, {
                method: 'GET',
                credentials: 'include'
            }).then(response).then(function (data) {
                dealerJson.push({
                    id: json.dealerId,
                    parentId: data.parentId,
                    brandId: json.brandId,
                    name: data.name,
                    createdAt: data.createdAt,
                    updatedAt: data.updatedAt
                });

                ReactDOM.render(React.createElement(DealerRows, { rows: dealerJson }), dealerRows);
            });
        }
    }

    $formHeading.submit(function (event) {

        event.preventDefault();

        var ids = $(this).find('select').val();

        for (var i in ids) {

            if (!ids.hasOwnProperty(i)) {
                continue;
            }

            var form = new FormData();
            form.append("id", ids[i]);

            fetch($formHeading.attr('action'), {
                method: $formHeading.attr('method'),
                credentials: 'include',
                body: form
            }).then(response).then(tableHeadingInit).catch(function () {
                return undefined;
            });
        }
    });

    $formDealer.submit(function (event) {

        event.preventDefault();

        var ids = $(this).find('select').val();

        for (var i in ids) {

            if (!ids.hasOwnProperty(i)) {
                continue;
            }

            var form = new FormData();
            form.append("id", ids[i]);

            fetch($formDealer.attr('action'), {
                method: $formDealer.attr('method'),
                credentials: 'include',
                body: form
            }).then(response).then(tableDealerInit).catch(function () {
                return undefined;
            });
        }
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

    $formAddress.submit(function (event) {

        event.preventDefault();

        $formAddress.find('input').prop('disabled', false);

        var form = new FormData(this);

        $formAddress.find('input:not(#autocomplete)').prop('disabled', true);
        $formAddress.find('button').prop('disabled', true);

        fetch($formAddress.attr('action'), {
            method: $formAddress.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableAddressInit).catch(function () {
            return undefined;
        });
    });

    fetch($formSocial.attr('action') + '?preload[]=social', {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(function () {
        return undefined;
    });

    fetch($formAddress.attr('action'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableAddressInit).catch(function () {
        return undefined;
    });

    fetch('/api/soc/heading?terms[brands.id]=' + $formHeading.data('id'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableHeadingInit).catch(function () {
        return undefined;
    });

    fetch('/api/soc/dealer?terms[brands.id]=' + $formDealer.data('id'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableDealerInit).catch(function () {
        return undefined;
    });
});
//# sourceMappingURL=edit.js.map