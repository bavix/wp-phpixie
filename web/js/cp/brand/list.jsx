// ReactDOM.render(
//     <h1>Hello, world!</h1>,
//     document.getElementById('root')
// );

let filter = {
    data: function (params) {
        return {
            term: params.term || "",
            page: params.page || 1
        }
    }
};