class IBoxBlock extends React.Component {

    constructor(props) {
        super(props);
    }

    render() {
        return <div className="col-md-3">
            <div className="ibox-title">
                {/*<span className="label label-success pull-right"> [BETA] </span>*/}
                <h5>{ this.props.title }</h5>
            </div>
            <div className="ibox-content">
                <h2 className="no-margins"> { this.props.count } </h2>
            </div>
        </div>
    }

}

$(function () {

    let brandCount = 'Loading..';
    let dealerCount = 'Loading..';
    let headingCount = 'Loading..';
    let userCount = 'Loading..';

    fetch('/cp/dashboard/count?model=brand', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then( res => {
        brandCount = res.count;
        render();
    } );

    fetch('/cp/dashboard/count?model=dealer', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then( res => {
        dealerCount = res.count;
        render();
    } );

    fetch('/cp/dashboard/count?model=heading', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then( res => {
        headingCount = res.count;
        render();
    } );

    fetch('/cp/dashboard/count?model=user', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then( res => {
        userCount = res.count;
        render();
    } );

    function render () {
        ReactDOM.render(
            <div className="col-md-12">
                <IBoxBlock title="Brand" count={brandCount} />
                <IBoxBlock title="Dealer" count={dealerCount} />
                <IBoxBlock title="Heading" count={headingCount} />
                <IBoxBlock title="User" count={userCount} />
            </div>,
            document.getElementById('content')
        );
    }

});