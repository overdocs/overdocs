(function (OverDocs) {
    'use strict';

    var Sheet = OverDocs.model.Sheet = Backbone.Model.extend({
        url: function () {
            return OverDocs.baseURL + '/' + this.get('path');
        },

        fetch: function () {
            var entry = OverDocs.sheetIndex.sheets[this.get('path')];

            this.set('title', entry.title);
            this.set('summary', entry.summary);
            this.set('keywords', entry.keywords);
            this.set('category', entry.category);
        }
    });
}(OverDocs));
