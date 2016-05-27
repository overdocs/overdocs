(function (OverDocs) {
    'use strict';

    OverDocs.getTemplate = function (name) {
        return _.template($('#' + name + '-template').text());
    };
}(OverDocs));
