(function (OverDocs) {
    'use strict';

    OverDocs.events.on('index', function () {
        var searchView = new OverDocs.view.SearchView({
            el: '.search-container'
        });
    });
}(OverDocs));
