"use strict";

// ReactDOM.render(
//     <h1>Hello, world!</h1>,
//     document.getElementById('root')
// );

var filter = {
    data: function data(params) {
        return {
            term: params.term || "",
            page: params.page || 1
        };
    }
};
//# sourceMappingURL=list.js.map