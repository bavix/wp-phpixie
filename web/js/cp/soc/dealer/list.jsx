class DealerRows extends React.Component {

    constructor(props) {
        super(props);
    }

    columns() {
        return <thead>
        <tr>
            <th>ID</th>
            <th>Parent Id</th>
            <th>Name</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
        </thead>;
    }

    row(model) {
        return <tr key={model.id}>
            <td>{model.id}</td>
            <td>{model.parentId}</td>
            <td>{model.name}</td>
            <td className="entry-date" data-time={ model.updatedAt }></td>
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

    const dealerRows = document.getElementById('content');
    let dealerJson = [];

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
            dealerJson = json;
        }
        else {
            dealerJson.push(json)
        }

        ReactDOM.render(
            <DealerRows rows={dealerJson}/>,
            dealerRows
        );
    }

    fetch('/api/soc/dealer?sort[id]=desc', {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(() => undefined);

});