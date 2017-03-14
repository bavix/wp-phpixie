class IBoxBlock extends React.Component {

    constructor(props) {
        super(props);
    }

    render() {
        return <div className="col-xs-3">
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

    let wheelCount = 'Loading..';
    let styleCount = 'Loading..';
    let boltPatternCount = 'Loading..';
    let collectionCount = 'Loading..';

    let userCount = 'Loading..';
    let roleCount = 'Loading..';
    let permissionCount = 'Loading..';

    let inviteCount = 'Loading..';
    let appCount = 'Loading..';

    fetch('/cp/dashboard/count?model=brand', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        brandCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=dealer', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        dealerCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=heading', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        headingCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=user', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        userCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=role', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        roleCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=permission', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        permissionCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=wheel', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        wheelCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=style', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        styleCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=boltPattern', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        boltPatternCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=collection', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        collectionCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=invite', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        inviteCount = res.count;
        render();
    });

    fetch('/cp/dashboard/count?model=app', {
        method: 'GET',
        credentials: 'include'
    }).then(r => r.json()).then(res => {
        appCount = res.count;
        render();
    });

    function render() {
        ReactDOM.render(
            <div className="col-lg-12">
                <IBoxBlock title="Brand" count={brandCount}/>
                <IBoxBlock title="Dealer" count={dealerCount}/>
                <IBoxBlock title="Heading" count={headingCount}/>
                <IBoxBlock title="Invite" count={inviteCount}/>

                <IBoxBlock title="Wheel" count={wheelCount}/>
                <IBoxBlock title="Style [wheels]" count={styleCount}/>
                <IBoxBlock title="Bolt Pattern [wheels]" count={boltPatternCount}/>
                <IBoxBlock title="Collection [wheels]" count={collectionCount}/>

                <IBoxBlock title="User" count={userCount}/>
                <IBoxBlock title="Role" count={roleCount}/>
                <IBoxBlock title="Permission" count={permissionCount}/>

                <IBoxBlock title="App" count={appCount}/>
            </div>,
            document.getElementById('content')
        );
    }

});