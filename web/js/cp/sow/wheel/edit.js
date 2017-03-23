"use strict";

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

var VideoRows = function (_React$Component) {
    _inherits(VideoRows, _React$Component);

    function VideoRows(props) {
        _classCallCheck(this, VideoRows);

        var _this = _possibleConstructorReturn(this, (VideoRows.__proto__ || Object.getPrototypeOf(VideoRows)).call(this, props));

        _this.state = {
            data: props.rows
        };
        return _this;
    }

    _createClass(VideoRows, [{
        key: "row",
        value: function row(object) {
            var model = object.model;
            return React.createElement(
                "li",
                { key: model.id, className: "col-xs-6 col-sm-4 col-md-3 video",
                    "data-trash": "/sow/wheel/" + object.id + "/video/" + model.id,
                    "data-src": model.url,
                    "data-sub-html": "<h4>" + model.description + "</h4>" },
                React.createElement(
                    "a",
                    null,
                    React.createElement("img", { className: "img-responsive", src: model.image }),
                    React.createElement(
                        "div",
                        { className: "gallery-list-poster" },
                        React.createElement("img", { src: "/node_modules/lightgallery/dist/img/video-play.png" })
                    )
                ),
                React.createElement(
                    "div",
                    { className: "caption" },
                    React.createElement(
                        "h4",
                        null,
                        " ",
                        model.title
                    )
                )
            );
        }
    }, {
        key: "render",
        value: function render() {

            var rows = this.props.rows.map(this.row);

            return React.createElement(
                "ul",
                { id: "videoGallery", className: "lightGallery" },
                rows
            );
        }
    }]);

    return VideoRows;
}(React.Component);

$(function () {

    $('[data-updated="wheel"]').submit(function (event) {

        event.preventDefault();

        var $self = $(this);
        var form = new FormData(this);

        fetch($self.attr('action'), {
            method: $self.attr('method'),
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

            // fixme
            alert('Информация обновлена!');
        }).catch(function (error) {
            var $message = $self.find('.alert');

            if (!$message.length) {
                $self.find('div:first-child').prepend('<div class="alert"></div>');
                $message = $self.find('.alert');
            }

            error.response.json().then(function (json) {
                return $message.addClass('alert-danger').text(json.error_description);
            });
        });
    });

    // fixme
    $('#styleWheel').select2({
        theme: "bootstrap"
    });

    // video

    var socialRows = document.getElementById('videoRows');
    var $formVideo = $('[data-created="video"]');

    var videoJson = [];
    var id = $formVideo.find('[name=id]').val();

    function tableInit(json) {
        if (typeof json.id === "undefined") {
            videoJson = json.data.map(function (model) {
                return {
                    id: id,
                    model: model
                };
            });
        } else {
            videoJson.push({
                id: id,
                model: json
            });
        }

        console.log(videoJson);

        ReactDOM.render(React.createElement(VideoRows, { rows: videoJson }), videoRows);

        $('#videoGallery').lightGallery({
            video: true
        });
    }

    // init data
    fetch($formVideo.attr('action') + '?terms[provider]=YouTube', {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(function () {
        return undefined;
    });

    // send form
    $formVideo.submit(function (event) {

        event.preventDefault();

        var form = new FormData(this);

        fetch($formVideo.attr('action'), {
            method: $formVideo.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableInit).catch(function () {
            return undefined;
        });
    });
});
//# sourceMappingURL=edit.js.map