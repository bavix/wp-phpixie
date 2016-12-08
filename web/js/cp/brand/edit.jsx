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
            <td>{ socialName }</td>
            <td>{model.url}</td>
            <td></td>
        </tr>;
    }

    render() {

        const rows = this.props.rows.map(this.row);

        const table = <table className="table table-striped">
            {this.columns()}
            <tbody>
            { rows }
            </tbody>
        </table>;

        return table;
    }
}

$(function () {

    const socialRows = document.getElementById('socialRows');
    let $formSocial = $('[data-created="social"]');
    let socialJson = [];

    $formSocial.submit(function (event) {

        event.preventDefault();

        let form = new FormData(this);

        fetch($formSocial.attr('action'), {
            method: $formSocial.attr('method'),
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

            socialJson.push(json);

            ReactDOM.render(
                <SocialRows rows={socialJson}/>,
                socialRows
            );

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

        let error = new Error(response.statusText);
        error.response = response;
        throw error;

    }).then(function (json) {

        socialJson = json;

        ReactDOM.render(
            <SocialRows rows={socialJson}/>,
            socialRows
        );

    }).catch(function (error) {
        // todo
    });

});