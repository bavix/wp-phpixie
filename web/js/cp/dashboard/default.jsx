class IBoxBlock extends React.Component {

    constructor(props) {
        super(props);
    }

    render() {

        let first = <span className="label label-primary pull-right">loading..</span>;
        let last;

        if (typeof this.props.data.count !== "undefined") {
            first = <span className="label label-primary pull-right">{ this.props.data.count }</span>;

            if (this.props.data.active !== this.props.data.count) {
                last = <span className="label label-danger pull-right">{ this.props.data.active }</span>;
            }
        }

        return <div className="col-sm-6 col-xs-4 col-md-4 col-lg-3">
            <div className="ibox-title">
                { first } { last }
                <h5>{ this.props.data.title }</h5>
            </div>
            <div className="ibox-content">
                <canvas id={"chart-" + this.props.data.id} width="100%"> Loading ...</canvas>
            </div>
        </div>
    }

}

function initChart(data) {
    new Chart(document.getElementById("chart-" + data.id).getContext('2d'), {
        type: 'pie',
        data: {
            labels: ["Active", "No Active"],
            datasets: [{
                backgroundColor: data.backgroundColor,
                data: data.data
            }]
        }
    });
}

$(function () {

    let blocks = {
        brand: {
            id: 'brand',
            title: 'Brand',
            backgroundColor: ['#e74c3c']
        },

        dealer: {
            id: 'dealer',
            title: 'Dealer',
            backgroundColor: ['#4BC0C0']
        },

        heading: {
            id: 'heading',
            title: 'Heading',
            backgroundColor: ['#36A2EB']
        },

        invite: {
            id: 'invite',
            title: 'Invite',
            backgroundColor: ['#c2c4d1']
        },

        wheel: {
            id: 'wheel',
            title: 'Wheel',
            backgroundColor: ['#ffe6ab']
        },

        style: {
            id: 'style',
            title: 'Style [Wheels]',
            backgroundColor: ['#36A2EB']
        },

        boltPattern: {
            id: 'boltPattern',
            title: 'Bolt Pattern [Wheels]',
            backgroundColor: ['#c2c4d1']
        },

        collection: {
            id: 'collection',
            title: 'Collection [Wheels]',
            backgroundColor: ['#4BC0C0']
        },

        user: {
            id: 'user',
            title: 'User',
            backgroundColor: ['#ffe6ab']
        },

        app: {
            id: 'app',
            title: 'Application',
            backgroundColor: ['#FF6384']
        }
    };

    for (let model in blocks) {
        fetch('/cp/dashboard/count?model=' + model, {
            method: 'GET',
            credentials: 'include'
        }).then(r => r.json()).then(res => {
            blocks[blocks[model].id].count = res.count;
            blocks[blocks[model].id].active = res.active;
            blocks[blocks[model].id].data = [res.active, res.count - res.active];
            render(blocks[blocks[model].id]);
        });
    }

    function render(data) {
        let content = document.getElementById('content');

        ReactDOM.render(
            <div className="col-lg-12">
                <IBoxBlock data={ blocks.brand }/>
                <IBoxBlock data={ blocks.dealer }/>
                <IBoxBlock data={ blocks.heading }/>
                <IBoxBlock data={ blocks.invite }/>

                <IBoxBlock data={ blocks.wheel }/>
                <IBoxBlock data={ blocks.style }/>
                <IBoxBlock data={ blocks.boltPattern }/>
                <IBoxBlock data={ blocks.collection }/>

                <IBoxBlock data={ blocks.user }/>
                <IBoxBlock data={ blocks.app }/>
            </div>,
            content
        );

        initChart(data);
    }

});