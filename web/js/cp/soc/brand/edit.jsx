class SocialRows extends React.Component {

    constructor(props) {
        super(props);
    }

   columns() {
        return <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>URL</th>
                <th>Actions</th>
            </tr>
        </thead>;
    }

    row(model) {
        const socialName = $('#socialType [value="' + model.socialId + '"]').text();
        return <tr key={model.id}>
            <td>{model.id}</td>
            <td>{socialName}</td>
            <td><a href={model.url} title={socialName} target="__blank">{model.url}</a></td>
            <td> </td>
        </tr>;
    }

    render() {

        const rows = this.props.rows.map(this.row);

        return <table className="table table-striped">
            {this.columns()}
            <tbody>
            { rows }
            </tbody>
        </table>;
    }
}

class HeadingRows extends React.Component {

    constructor(props) {
        super(props);
    }

    columns() {
        return <thead>
        <tr>
            <th>ID</th>
            <th>Parent ID</th>
            <th>Title</th>
            <th>Actions</th>
        </tr>
        </thead>;
    }

    row(model) {
        return <tr key={model.id}>
            <td>{model.id}</td>
            <td>{model.parentId}</td>
            <td>{model.title}</td>
            <td> </td>
        </tr>;
    }

    render() {

        const rows = this.props.rows.map(this.row);

        return <table className="table table-striped">
            {this.columns()}
            <tbody>
            { rows }
            </tbody>
        </table>;
    }
}

$(function () {

    // heading

    $('#headingType').select2({
        theme: "bootstrap",
        ajax: {
            url: '/api/soc/heading?limit=15',
            dataType: 'json',
            delay: 350,
            data: function (params) {
                return {
                    queries: {
                        title: params.term
                    },
                    page: params.page || 1
                }
            },
            processResults: function (data) {
                if (typeof data.message !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data, function (obj) {
                        return {id: obj.id, text: obj.title};
                    })
                };
            },
            cache: true
        }
    });

    /// social
    const socialRows = document.getElementById('socialRows');
    let $formSocial = $('[data-created="social"]');

    // heading
    const headingRows = document.getElementById('headingRows');
    let $formHeading = $('[data-created="heading"]');

    let socialJson = [];
    let headingJson = [];

    function response(response) {
        if (response.status === 201 || response.status === 200) {
            return response.json();
        }

        let error = new Error(response.statusText);
        error.response = response;
        throw error;
    }

    function tableInit(json) {
        if (typeof json.id === "undefined") {
            socialJson = json;
        }
        else {
            socialJson.push(json)
        }

        ReactDOM.render(
            <SocialRows rows={socialJson}/>,
            socialRows
        );
    }

    function tableHeadingInit(json) {
        if (typeof json.id === "undefined") {
            headingJson = json;

            ReactDOM.render(
                <HeadingRows rows={headingJson}/>,
                headingRows
            );
        }
        else {
            fetch('/api/soc/heading/' + json.headingId, {
                method: 'GET',
                credentials: 'include'
            }).then( response ).then( data => {
                headingJson.push( {
                    id: json.headingId,
                    parentId: data.parentId,
                    title: data.title,
                    createdAt: data.createdAt,
                    updatedAt: data.updatedAt
                } );

                ReactDOM.render(
                    <HeadingRows rows={headingJson}/>,
                    headingRows
                );
            } );
        }
    }

    $formHeading.submit(function (event) {

        event.preventDefault();

        let form = new FormData(this);

        fetch($formHeading.attr('action'), {
            method: $formHeading.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableHeadingInit).catch(() => undefined);

    });

    $formSocial.submit(function (event) {

        event.preventDefault();

        let form = new FormData(this);

        fetch($formSocial.attr('action'), {
            method: $formSocial.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableInit).catch(() => undefined);

    });

    fetch($formSocial.attr('action'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(() => undefined);

    fetch('/api/soc/heading?terms[brands.id]=' + $formHeading.data('id'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableHeadingInit).catch(() => undefined);

});