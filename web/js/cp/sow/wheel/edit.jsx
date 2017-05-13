function response(response) {
    if (response.status === 201 || response.status === 200) {
        return response.json();
    }

    if (response.status !== 204) {
        let error = new Error(response.statusText);
        error.response = response;
        throw error;
    }

    return [];
}

class VideoRows extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            data: props.rows
        };
    }

    row(object) {
        let model = object.model;
        return <li key={model.id} className="col-xs-6 col-sm-4 col-md-3 video"
                   data-trash={"/sow/wheel/" + object.id + "/video/" + model.id}
                   data-src={model.url}
                   data-sub-html={"<h4>" + model.description + "</h4>"}>
            <a>
                <img className="img-responsive" src={model.image}/>
                <div className="gallery-list-poster">
                    <img src="/node_modules/lightgallery/dist/img/video-play.png"/>
                </div>
            </a>

            <div className="caption">
                <h4> {model.title}</h4>
            </div>
        </li>
    }

    render() {

        const rows = this.props.rows.map(this.row);

        return <ul id="videoGallery" className="lightGallery" >
            { rows }
        </ul>;
    }
}

$(function () {

    $('[data-updated="wheel"]').submit(function (event) {

        event.preventDefault();

        let $self = $(this);
        let form = new FormData(this);

        fetch($self.attr('action'), {
            method: $self.attr('method'),
            credentials: 'include',
            body: form
        }).then(function (response) {
            if (response.status === 201 || response.status === 200) {
                return response.json();
            }

            let error = new Error(response.statusText);
            error.response = response;
            throw error;
        }).then(function (json) {

            // fixme
            alert('Информация обновлена!');

        }).catch(function (error) {
            let $message = $self.find('.alert');

            if (!$message.length) {
                $self.find('div:first-child').prepend('<div class="alert"></div>');
                $message = $self.find('.alert');
            }

            error.response.json().then((json) => $message.addClass('alert-danger').text(json.error_description));
        });

    });

    // fixme
    $('.select2-multiple').select2({
        theme: "bootstrap"
    });

    // video

    const socialRows = document.getElementById('videoRows');
    let $formVideo = $('[data-created="video"]');

    let videoJson = [];
    let id = $formVideo.find('[name=id]').val();

    function tableInit(json) {
        if (typeof json.id === "undefined") {
            videoJson = json.data.map(function (model) {
                return {
                    id: id,
                    model: model
                };
            });
        }
        else {
            videoJson.push({
                id: id,
                model: json
            })
        }

        console.log(videoJson);

        ReactDOM.render(
            <VideoRows rows={videoJson}/>,
            videoRows
        );

        $('#videoGallery').lightGallery({
            video: true
        });

    }

    // init data
    fetch($formVideo.attr('action') + '?terms[provider]=YouTube', {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(() => undefined);

    // send form
    $formVideo.submit(function (event) {

        event.preventDefault();

        let form = new FormData(this);

        fetch($formVideo.attr('action'), {
            method: $formVideo.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableInit).catch(() => undefined);

    });

});