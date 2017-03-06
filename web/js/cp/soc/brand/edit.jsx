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

let brandId;

class ButtonDelete extends React.Component {

    constructor(props) {
        super(props);

        this.methodDelete = this.methodDelete.bind(this);
    }

    methodDelete(event) {

        let node = event.target.parentNode.parentNode;

        if (node.tagName != 'TR') {
            node = node.parentNode
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
        }).then(() => {

            fetch(this.props.api, {
                method: 'DELETE',
                credentials: 'include'
            }).then(response).then(json => {

                $(node).remove();
                swal(
                    'Deleted!',
                    'Your data has been deleted.',
                    'success'
                );

            }).catch(function () {
                swal(
                    'Deleted!',
                    'Your data has not been deleted.',
                    'error'
                );
            });

        });

    }

    render() {
        return <a onClick={ this.methodDelete } className="btn btn-danger">
            <i className="fa fa-trash"> </i> Delete
        </a>
    }
}

class SocialRows extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            data: props.rows
        };
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
        let socialName;
        if (typeof model.social === "undefined") {
            socialName = $('#socialType [value="' + model.socialId + '"]').text();
        }
        else {
            socialName = model.social.title;
        }
        return <tr key={model.id}>
            <td>{model.id}</td>
            <td>{socialName}</td>
            <td><a href={model.url} title={socialName} target="__blank">{model.url}</a></td>
            <td>
                <ButtonDelete key={model.id} api={'/api/soc/brand/' + model.brandId + '/social/' + model.socialId}/>
            </td>
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
            <td>
                <ButtonDelete key={model.id} api={'/api/soc/brand/' + model.brandId + '/heading/' + model.id}/>
            </td>
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

class DealerRows extends React.Component {

    constructor(props) {
        super(props);
    }

    columns() {
        return <thead>
        <tr>
            <th>ID</th>
            <th>Parent ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>;
    }

    row(model) {
        return <tr key={model.id}>
            <td>{model.id}</td>
            <td>{model.parentId}</td>
            <td>{model.name}</td>
            <td>
                <ButtonDelete key={model.id} api={'/api/soc/brand/' + model.brandId + '/dealer/' + model.id}/>
            </td>
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

class AddressRows extends React.Component {

    constructor(props) {
        super(props);
    }

    columns() {
        return <thead>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Country</th>
            <th>City</th>
            <th>Street</th>
            <th>Number Street</th>
        </tr>
        </thead>;
    }

    row(model) {
        return <tr key={model.id}>
            <td>{model.id}</td>
            <td>{model.description}</td>
            <td>{model.country}</td>
            <td>{model.city}</td>
            <td>{model.street}</td>
            <td>{model.streetNumber}</td>
            <td>
                <ButtonDelete key={model.id} api={'/api/soc/brand/' + brandId + '/address/' + model.id}/>
            </td>
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
                if (typeof data.error_description !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data.data, function (obj) {
                        return {id: obj.id, text: obj.title};
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
            data: function (params) {
                return {
                    queries: {
                        name: params.term
                    },
                    page: params.page || 1
                }
            },
            processResults: function (data) {
                if (typeof data.error_description !== "undefined") {
                    return {
                        results: {}
                    };
                }

                return {
                    results: $.map(data.data, function (obj) {
                        return {id: obj.id, text: obj.name};
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

    // dealer
    const dealerRows = document.getElementById('dealerRows');
    let $formDealer = $('[data-created="dealer"]');

    // address
    const addressRows = document.getElementById('addressRows');
    let $formAddress = $('[data-created="address"]');

    let socialJson = [];
    let headingJson = [];
    let dealerJson = [];
    let addressJson = [];

    brandId = $formDealer.data('id');

    function tableInit(json) {
        if (typeof json.id === "undefined") {
            socialJson = json.data;
        }
        else {
            socialJson.push(json)
        }

        ReactDOM.render(
            <SocialRows rows={socialJson}/>,
            socialRows
        );
    }

    function tableAddressInit(json) {
        if (typeof json.id === "undefined") {
            addressJson = json.data;
        }
        else {
            addressJson.push(json)
        }

        ReactDOM.render(
            <AddressRows rows={addressJson}/>,
            addressRows
        );
    }

    function tableHeadingInit(json) {
        if (typeof json.data !== "undefined") {

            json = json.data.map(data => {
                data.brandId = $formHeading.data('id');

                return data;
            });

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
            }).then(response).then(data => {
                headingJson.push({
                    id: json.headingId,
                    parentId: data.parentId,
                    brandId: json.brandId,
                    title: data.title,
                    createdAt: data.createdAt,
                    updatedAt: data.updatedAt
                });

                ReactDOM.render(
                    <HeadingRows rows={headingJson}/>,
                    headingRows
                );
            });
        }
    }

    function tableDealerInit(json) {
        if (typeof json.data !== "undefined") {

            json = json.data.map(data => {
                data.brandId = $formDealer.data('id');

                return data;
            });

            dealerJson = json;

            ReactDOM.render(
                <DealerRows rows={dealerJson}/>,
                dealerRows
            );
        }
        else {
            fetch('/api/soc/dealer/' + json.dealerId, {
                method: 'GET',
                credentials: 'include'
            }).then(response).then(data => {
                dealerJson.push({
                    id: json.dealerId,
                    parentId: data.parentId,
                    brandId: json.brandId,
                    name: data.name,
                    createdAt: data.createdAt,
                    updatedAt: data.updatedAt
                });

                ReactDOM.render(
                    <DealerRows rows={dealerJson}/>,
                    dealerRows
                );
            });
        }
    }

    $formHeading.submit(function (event) {

        event.preventDefault();

        let ids = $(this).find('select').val();

        for (let i in  ids) {

            if (!ids.hasOwnProperty(i)) {
                continue;
            }

            let form = new FormData();
            form.append("id", ids[i]);

            fetch($formHeading.attr('action'), {
                method: $formHeading.attr('method'),
                credentials: 'include',
                body: form
            }).then(response).then(tableHeadingInit).catch(() => undefined);
        }

    });

    $formDealer.submit(function (event) {

        event.preventDefault();

        let ids = $(this).find('select').val();

        for (let i in  ids) {

            if (!ids.hasOwnProperty(i)) {
                continue;
            }

            let form = new FormData();
            form.append("id", ids[i]);

            fetch($formDealer.attr('action'), {
                method: $formDealer.attr('method'),
                credentials: 'include',
                body: form
            }).then(response).then(tableDealerInit).catch(() => undefined);

        }

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

    $formAddress.submit(function (event) {

        event.preventDefault();

        $formAddress.find('input').prop('disabled', false);

        let form = new FormData(this);

        $formAddress.find('input:not(#autocomplete)').prop('disabled', true);
        $formAddress.find('button').prop('disabled', true);

        fetch($formAddress.attr('action'), {
            method: $formAddress.attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(tableAddressInit).catch(() => undefined);

    });

    $('[data-updated="brand"]').submit(function () {

        event.preventDefault();

        let form = new FormData(this);

        fetch($(this).attr('action'), {
            method: $(this).attr('method'),
            credentials: 'include',
            body: form
        }).then(response).then(function (response) {

            alert('Информация обновлена!');
            console.log(response);

        }).catch(() => undefined);

    });

    fetch($formSocial.attr('action') + '?preload[]=social', {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableInit).catch(() => undefined);

    fetch($formAddress.attr('action'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableAddressInit).catch(() => undefined);

    fetch('/api/soc/heading?terms[brands.id]=' + $formHeading.data('id'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableHeadingInit).catch(() => undefined);

    fetch('/api/soc/dealer?terms[brands.id]=' + $formDealer.data('id'), {
        method: 'GET',
        credentials: 'include'
    }).then(response).then(tableDealerInit).catch(() => undefined);

});